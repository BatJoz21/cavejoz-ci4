<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\FriendshipApiService;
use App\Service\PostApiService;
use App\Service\UserApiService;

class Users extends BaseController
{
    private UserApiService $api;
    private PostApiService $postApi;
    private FriendshipApiService $friendshipApi;

    public function __construct()
    {
        $this->api = new UserApiService();
        $this->postApi = new PostApiService();
        $this->friendshipApi = new FriendshipApiService();
    }

    public function openMyProfile()
    {
        // Call the API to get profile data
        $response = $this->api->getMyProfile();
        if(!$response['success']) {
            return redirect()->to('/')
                             ->with('error', $response['message']);
        }
        $data = $response['data'];
        
        // Call the API to get total post and friend data
        $data['total_post'] = $this->postApi->getTotalPostByUId($data['id'])['data'] ?? 0;
        $data['total_friend'] = $this->friendshipApi->getTotalFriendByUId($data['id'])['data'] ?? 0;

        // Call the API to get user's posts
        $responsePost = $this->postApi->getUserPosts($data['id']);
        if(!$responsePost['success']) {
            return redirect()->to('/')
                             ->with('error', 'Failed to get post data: ' . $responsePost['message']);
        }

        return view('Users/profile', [
            'data'  => $data,
            'posts' => $responsePost['data']
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
}
