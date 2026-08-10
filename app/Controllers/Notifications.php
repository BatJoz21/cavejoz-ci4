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
        // Call the API to get notifications
        $response = $this->api->getUserNotifications();
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', $response['message']);
        }

        return view('Notifications/index', ['notifications' => $response['data']]);
    }

    public function latestNotification()
    {
        // Call the API to get notifications
        $response = $this->api->getNotificationWithLimit(4);

        // Return it in JSON format for AJAX
        return $this->response->setJSON([
            'success'           => $response['success'],
            'notifications'     => $response['data'] ?? []
        ]);
    }

    public function markAllRead()
    {
        // Call the API to mark read all notifications
        $response = $this->api->markAllNotificationRead();
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Failed to mark read notifications');
        }

        return redirect()->back()
                         ->with('message', $response['data']['message']);
    }

    public function markReadNotification(int $id)
    {
        // Call the API to mark read all notification by that notification's id
        $response = $this->api->markNotificationRead($id);
        if(!$response['success']) {
            return redirect()->to('/')
                             ->with('error', 'Unable to open that notification');
        }

        return redirect()->to(get_notification_url($response['data']['notification']));
    }
}
