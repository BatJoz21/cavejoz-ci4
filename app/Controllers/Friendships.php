<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\FriendshipApiService;

class Friendships extends BaseController
{
    private FriendshipApiService $api;

    public function __construct()
    {
        $this->api = new FriendshipApiService();
    }

    public function index()
    {
        // Get status from query parameters
        $status = $this->request->getGet('status') ?? 'accepted';
        $response = [];

        // Call the API based on $status
        if($status == 'accepted') {
            $response = $this->api->getFriendsList();
        } elseif($status == 'pending') {
            $response = $this->api->getPendingFriendList();
        } elseif($status == 'blocked') {
            $response = $this->api->getBlockedList();
        }

        // Handle failed response
        if(!$response['success']) {
            return redirect()->to('/')
                                ->with('error', $response['message']);
        }
        // Get response's data if success
        $users = $response['data'];

        return view('Friendships/index', [
            'status'    => $status,
            'users'     => $users
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
                             ->with('error', $response['message']);
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
                             ->with('error', $response['message']);
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
                             ->with('error', $response['message']);
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
                             ->with('error', $response['message']);
        }

        return redirect()->to('/friends')
                         ->with('message', 'Friend removed');
    }
}
