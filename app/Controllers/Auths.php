<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\AuthApiService;

class Auths extends BaseController
{
    /**
     * Login throttling. Both buckets refill continuously over LOGIN_WINDOW, so
     * the sustained rate is capacity/window: 1 attempt per minute per account,
     * 4 per minute per IP. The burst allowance is the capacity itself, which
     * leaves room for a user who mistypes a few times.
     */
    private const LOGIN_WINDOW           = 5 * MINUTE;
    private const LOGIN_ACCOUNT_ATTEMPTS = 5;
    private const LOGIN_IP_ATTEMPTS      = 20;

    private AuthApiService $api;

    public function __construct()
    {
        $this->api = new AuthApiService();
    }

    public function openRegisterPage()
    {
        return view('Auth/register');
    }

    public function registerNewUser()
    {
        // User Input Validation
        $rules = config('Validation')->newUser;
        if(!$this->validate($rules)) {
            return redirect()->to('register')
                             ->with('errors', $this->validator->getErrors())
                             ->withInput();
        }

        // Get Avatar Image File
        $avatar = $this->request->getFile('avatar');
        
        // Handle API response and redirect with success/error message
        $response = $this->api->register([
            'username'          => $this->request->getPost('username'),
            'email'             => $this->request->getPost('email'),
            'password'          => $this->request->getPost('password'),
            'full_name'         => $this->request->getPost('full_name')
        ], $avatar);

        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'An error has occured during user registration')
                             ->withInput();
        }

        // Redirect to login page
        return redirect()->to('/login')
                         ->with('message', $response['data']['message']);
    }

    public function openLoginPage()
    {
        return view('Auth/login');
    }

    public function userLogin()
    {
        // Validate user's input
        $rules = config('Validation')->userLogin;
        if(!$this->validate($rules)) {
            return redirect()->back()
                             ->with('errors', $this->validator->getErrors())
                             ->withInput();
        }

        // Build the throttle keys. Both are hashed because cache keys may not
        // contain the reserved characters ':' (IPv6 addresses) or '@' (emails).
        // NOTE: getIPAddress() returns the socket address. If this app is ever
        // put behind a reverse proxy or CDN, populate Config\App::$proxyIPs or
        // every visitor will share the one IP bucket.
        $email      = strtolower(trim((string) $this->request->getPost('email')));
        $ipKey      = 'login-ip-' . hash('sha256', $this->request->getIPAddress());
        $accountKey = 'login-account-' . hash('sha256', $email);

        // Reject the attempt if either bucket is empty. The short-circuit is
        // deliberate: a request already blocked by IP must not also spend a
        // token from the account bucket.
        $throttler = service('throttler');
        if(
            !$throttler->check($ipKey, self::LOGIN_IP_ATTEMPTS, self::LOGIN_WINDOW)
            || !$throttler->check($accountKey, self::LOGIN_ACCOUNT_ATTEMPTS, self::LOGIN_WINDOW)
        ) {
            return redirect()->back()
                             ->with('error', 'Too many login attempts. Please try again in ' . $throttler->getTokenTime() . ' second(s).')
                             ->withInput();
        }

        // Get device information
        $agent = $this->request->getUserAgent();
        $device_name = 'Unknown device';
        if($agent->isBrowser()) {
            if($agent->isMobile()) {
                $device_name = $agent->getMobile() . '|' . $agent->getPlatform() . '|' . $agent->getBrowser() . ', ' . $agent->getVersion();
            } else {
                $device_name = $agent->getPlatform() . '|' . $agent->getBrowser() . ', ' . $agent->getVersion();
            }
        }

        // Get API response
        $response = $this->api->login([
            'email'         => $this->request->getPost('email'),
            'password'      => $this->request->getPost('password'),
            'device_name'   => $device_name,
        ]);

        // Handle failed response
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'An error has occured during user login');
        }

        // Credentials were good, so clear both buckets. Without this a user who
        // signs in normally would still spend their own allowance.
        $throttler->remove($ipKey);
        $throttler->remove($accountKey);

        // Set variable and it's values in session
        session()->regenerate(true);
        session()->set([
            'logged_in'     => true,
            'access_token'  => $response['data']['access_token'],
            'refresh_token' => $response['data']['refresh_token'],
            'user'          => $response['data']['user']
        ]);

        // Redirect to home page
        return redirect()->to('/')
                         ->with('message', 'Login successful');
    }

    public function userLogout()
    {
        // Check if user is logged in
        if(!session('logged_in')) {
            return redirect()->to('login')
                             ->with('message', "You're not logged in!");
        }

        // Call the API for logout
        $response = $this->api->logout();

        // Destory the session
        session()->destroy();

        // Return to the login page
        return redirect()->to('login')
                         ->with('message', "Logged out");
    }
}
