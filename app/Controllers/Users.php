<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\ConversationApiService;
use App\Service\FriendshipApiService;
use App\Service\UserApiService;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ResponseException;

class Users extends BaseController
{
    private UserApiService $api;
    private Posts $post;
    private FriendshipApiService $friendshipApi;
    private ConversationApiService $cApi;

    public function __construct()
    {
        $this->api = new UserApiService();
        $this->post = new Posts();
        $this->friendshipApi = new FriendshipApiService();
        $this->cApi = new ConversationApiService();
    }

    public function openMyProfile()
    {
        // Call the API to get profile data
        $response = $this->api->getMyProfile();
        if(!$response['success']) {
            return redirect()->to('/')
                             ->with('error', 'Failed to open your profile');
        }
        $data = $response['data'];
        
        // Call the API to get total post and friend data
        $data['total_post'] = $this->post->getTotalPostOfUser($data['id']);
        $data['total_friend'] = $this->friendshipApi->getTotalFriendByUId($data['id'])['data'] ?? 0;

        // Call the method to get user's posts
        $posts = $this->post->loadPostsDataForPostCard($data['id']);

        return view('Users/profile', [
            'data'  => $data,
            'posts' => $posts
        ]);
    }

    public function openUserProfile(string $username)
    {
        // Call the API to get profile data
        $response = $this->api->getUserProfile($username);
        if(!$response['success']) {
            return redirect()->to('/')
                             ->with('error', 'Failed to open a user profile');
        }
        $data = $response['data'];

        // Call the API to get friendship status with logged in user
        $data['friendship_status'] = $this->friendshipApi->getFriendshipStatus($data['id'])['data'] ?? '';
        if($data['friendship_status'] === 'blocked') {
            return redirect()->to('/')
                             ->with('error', "Unable to open this user's profile");
        }

        // Call the API to get total post and friend data
        $data['total_post'] = $this->post->getTotalPostOfUser($data['id']);
        $data['total_friend'] = $this->friendshipApi->getTotalFriendByUId($data['id'])['data'] ?? 0;

        // Call the method to get user's posts
        $posts = $this->post->loadPostsDataForPostCard($data['id']);

        // call the API to get conversation ID
        $cID = $this->cApi->getConversationID($data['id'])['data'] ?? 0;
        
        return view('Users/profile', [
            'data'  => $data,
            'posts' => $posts,
            'cID'   => $cID
        ]);
    }

    public function getUserAvatar(string $filename)
    {
        try {
            // Call the API to get the avatar
            $response = $this->api->getUserAvatar($filename);

            // Get the response's body
            $body = (string) $response->getBody();

            // Verify the image type
            $info = @getimagesizefromstring($body);
            if($info === false || !isset(self::ALLOWED_IMAGE_TYPES[$info[2]])) {
                log_message('warning', 'Non image content served from the image route', [
                    'file'      => $filename,
                    'api_type'  => $response->getHeaderLine('Content-Type'),
                ]);

                return $this->response->setStatusCode(415)->setBody('');
            }

            // Set up to show the image
            $mime = self::ALLOWED_IMAGE_TYPES[$info[2]];
            return $this->response->setStatusCode(200)
                                  ->setHeader('Content-Type', $mime)
                                  ->setHeader('Content-Length', strlen($body))
                                  ->setHeader('X-Content-Type-Option', 'nosniff')
                                  ->setHeader('Content-Security-Policy', "default-src 'none'; sandbox")
                                  ->setBody($body);
        } catch(ConnectException $e) {
            // API unreachable
            log_message('error', 'Failed to connect to the API', [
                'file'      => $filename,
                'message'   => $e->getMessage(),
            ]);

            return $this->response->setStatusCode(503)->setBody('');
        } catch(ResponseException $e) {
            // API responded with an error status code
            $statusCode = $e->getResponse()->getStatusCode() ?? 502;

            log_message('error', 'An error has occured', [
                'status'    => $statusCode,
                'file'      => $filename,
            ]);

            return $this->response->setStatusCode($statusCode == 404 ? 404 : 502)->setBody('');
        } catch(RequestException $e) {
            // API request failed before any response
            log_message('error', 'Image request failed before response', [
                'file'      => $filename,
                'message'   => $e->getMessage(),
            ]);

            return $this->response->setStatusCode(502)->setBody('');
        } catch(\Throwable $e) {
            log_message('critical', 'Unexpected image proxy failure', [
                'message'   => $e->getMessage(),
            ]);

            return $this->response->setStatusCode(500)->setBody('');
        }
    }

    public function edit()
    {
        // Call the API and handle it's response
        $response = $this->api->getMyProfile();
        if(!$response['success']) {
            return redirect()->to('/profile')
                             ->with('error', 'An error has occured');
        }

        return view('Users/edit-profile', [
            'user'  => $response['data']
        ]);
    }

    public function update()
    {
        // Validate user's input
        $rules = config('Validation')->updateProfile;
        if(!$this->validate($rules)) {
            return redirect()->back()
                             ->with('errors', $this->validator->getErrors())
                             ->withInput();
        }

        // Get Avatar Image File
        $avatar = $this->request->getFile('avatar');

        // Handle API response and redirect with success/error message
        $response = $this->api->editUserData([
            'username'      => $this->request->getPost('username'),
            'full_name'     => $this->request->getPost('full_name'),
            'bio'           => $this->request->getPost('bio')
        ], $avatar);

        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Failed to edit your profile')
                             ->withInput();
        }

        return redirect()->to('/profile')
                         ->with('message', $response['data']['message']);
    }
}
