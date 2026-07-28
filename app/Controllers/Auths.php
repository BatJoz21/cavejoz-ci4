<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\AuthApiService;

class Auths extends BaseController
{
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
        // ToDo: User Input Validation

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
                             ->with('error', $response['message'])
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
        // Get API response
        $response = $this->api->login([
            'email'     => $this->request->getPost('email'),
            'password'  => $this->request->getPost('password')
        ]);

        // Handle failed response
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', $response['message']);
        }

        // Set variable and it's values in session
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

        // Handle failed response
        if(!$response['success']) {
            return redirect()->to('/')
                             ->with('error', $response['message']);
        }

        // Destory the session
        session()->destroy();

        // Return to the login page
        return redirect()->to('login')
                         ->with('message', $response['data']['message']);
    }
}
