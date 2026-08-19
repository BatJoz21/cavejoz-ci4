<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\FriendshipApiService;

class Friendships extends BaseController
{
    private FriendshipApiService $api;
    private Conversations $conversations;

    public function __construct()
    {
        $this->api = new FriendshipApiService();
        $this->conversations = new Conversations();
    }

    public function index()
    {
        // Get status from query parameters
        $status = $this->request->getGet('status') ?? 'accepted';
        $response = [];

        // Get page from query parameters
        $page = $this->request->getGet('page') ?? '1';

        // Call the API based on $status
        if($status == 'accepted') {
            $response = $this->api->getFriendsList($page);
        } elseif($status == 'pending') {
            $response = $this->api->getPendingFriendList($page);
        } elseif($status == 'blocked') {
            $response = $this->api->getBlockedList($page);
        }

        // Handle failed response
        if(!$response['success']) {
            return redirect()->to('/')
                                ->with('error', 'An error has occured');
        }
        // Get response's data if success
        $users = $response['data']['data'];

        $totalPage = ceil($response['data']['total'] / 10);

        return view('Friendships/index', [
            'status'        => $status,
            'users'         => $users,
            'currentPage'   => $page,
            'totalPage'     => $totalPage
        ]);
    }

    public function addFriendAUser()
    {
        // Get addresse ID from post form
        $addresseID = $this->request->getPost('addressee_id');
        
        // Call the API and handle the response
        $response = $this->api->addFriend($addresseID);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Failed to add friend this user');
        }

        return redirect()->to('/friends')
                         ->with('message', 'Friend request sent');
    }

    public function blockAUser()
    {
        // Get addresse ID from post form
        $addresseID = $this->request->getPost('addressee_id');

        // Call the API and handle the response
        $response = $this->api->blockUser($addresseID);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Failed to block a user');
        }

        return redirect()->to('/friends')
                         ->with('message', 'User blocked');
    }

    public function acceptFriendRequest()
    {
        // Get friendship ID from post form
        $frID = $this->request->getPost('friendship_id');

        // Call the API and handle the response
        $response = $this->api->acceptFriend($frID);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Failed to accept friend request');
        }

        // Create a new conversation
        $isCreated = $this->conversations->createConversation($response['data']['reqID']);
        if(!$isCreated) {
            return redirect()->back()
                             ->with('error', 'Failed to create a chat room with your new friend');
        }

        return redirect()->to('/friends')
                         ->with('message', 'Friend request accepted');
    }

    public function rejectRemoveFriendRequest()
    {
        // Get friendship ID from post form
        $frID = $this->request->getPost('friendship_id');

        // Call the API and handle the response
        $response = $this->api->rejectOrRemoveFriend($frID);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Failed: an error has occured');
        }

        return redirect()->to('/friends')
                         ->with('message', 'Friend removed');
    }
}
