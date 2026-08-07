<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\NotificationApiService;

class Notifications extends BaseController
{
    private NotificationApiService $api;

    public function __construct()
    {
        $this->api = new NotificationApiService();
    }

    public function index()
    {
        $response = $this->api->getUserNotifications();
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', $response['message']);
        }

        return view('Notifications/index', ['notifications' => $response['data']]);
    }

    public function latestNotification()
    {
        $response = $this->api->getNotificationWithLimit(4);
        if(!$response['success']) {
            return $this->response->setJSON([
                'success'       => false,
                'notifications' => []
            ]);
        }

        return $this->response->setJSON([
            'success'           => true,
            'notifications'     => $response['data']
        ]);
    }
}
