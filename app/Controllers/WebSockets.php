<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\WebSocketApiService;

class WebSockets extends BaseController
{
    private WebSocketApiService $api;

    public function __construct()
    {
        $this->api = new WebSocketApiService();
    }

    public function getWebSocketTicket()
    {
        // Checked if user is logged in
        if(!session('logged_in') || empty(session('access_token'))) {
            return $this->response->setStatusCode(401)
                                  ->setJSON(['message' => 'Not authorized']);
        }

        // Call the API to get generated web socket ticket
        $response = $this->api->fetchWSTicket();
        if(!$response['success']) {
            return $this->response->setStatusCode(503)
                                  ->setJSON(['message' => 'WS service unavailable']);
        }

        // Return in JSON format
        return $this->response->setJSON(['ticket' => $response['data']['ticket']]);
    }
}
