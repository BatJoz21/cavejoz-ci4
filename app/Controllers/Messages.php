<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\MessageApiService;

class Messages extends BaseController
{
    private MessageApiService $api;

    public function __construct()
    {
        $this->api = new MessageApiService();
    }

    public function sendMessage(int $cID)
    {
        // Get user's input
        $message = $this->request->getPost('messageInput');

        // Call the API to send message
        $response = $this->api->sendMessageRoute($cID, $message);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Failed to send message');
        }

        return redirect()->back();
    }

    public function getConversationMessage(int $cID)
    {
        $cursor = (int) $this->request->getGet('cursor') ?? 0;

        // Call the API to get messages by conversation ID
        $response = $this->api->getMessageByCID($cID, $cursor);
        if(!$response['success']) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => "Failed to get message"
            ]);
        }

        // Return in JSON format
        return $this->response->setJSON([
            'success'       => true,
            'messages'      => $response['data']['messages'],
            'next_cursor'   => $response['data']['next_cursor']
        ]);
    }
}
