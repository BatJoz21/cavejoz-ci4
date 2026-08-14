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
        $message = $this->request->getPost('messageInput');

        $response = $this->api->sendMessageRoute($cID, $message);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Failed to send message');
        }

        return redirect()->back();
    }
}
