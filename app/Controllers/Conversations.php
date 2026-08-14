<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\ConversationApiService;

class Conversations extends BaseController
{
    private ConversationApiService $api;

    public function __construct()
    {
        $this->api = new ConversationApiService();
    }

    public function createConversation(string $friendID)
    {
        // Call the API to create a conversation
        $response = $this->api->createConversation($friendID);
        if(!$response['success']) {
            return false;
        }

        return true;
    }

    public function getConversationList()
    {
        // Get page from url query
        $page = $this->request->getGet('page') ?? "1";
        if(!is_numeric($page)) {
            return redirect()->back()
                             ->with('error', 'Invalid page number');
        }

        // Call the API to get all the data
        $response = $this->api->getConversations($page);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', $response['message']);
        }

        // Count total available page
        $totalPage = ceil($response['data']['total'] / $response['data']['max']);

        // Get current path for toggle add post button visibility
        $currentPath = uri_string();
        
        return view('Conversations/index', [
            'conversations' => $response['data']['conversations'],
            'currentPage'   => $page,
            'totalPage'     => $totalPage,
            'currentPath'   => $currentPath
        ]);
    }

    public function openConversation(int $cID)
    {
        // Call the API to get conversation and messages data
        $response = $this->api->getConversation($cID);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', $response['message']);
        }

        // Get current path for toggle add post button visibility
        $currentPath = uri_string();

        return view('Conversations/thread', [
            'conversation'  => $response['data']['conversation'],
            'messages'      => $response['data']['messages'],
            'currentPath'   => $currentPath
        ]);
    }
}
