<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\ConversationApiService;
use App\Service\FriendshipApiService;
use App\Service\UserApiService;

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
        // Call the API to get the avatar
        $response = $this->api->getUserAvatar($filename);

        // Get the response's body
        $body = (string) $response->getBody();

        // Set up to show the image
        return $this->response->setStatusCode(200)
                              ->setHeader('Content-Type', $response->getHeaderLine('Content-Type'))
                              ->setHeader('Content-Length', strlen($body))
                              ->setBody($body);
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
