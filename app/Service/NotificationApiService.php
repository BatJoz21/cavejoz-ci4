<?php

namespace App\Service;

class NotificationApiService extends BaseApiService
{
    public function getUserNotifications()
    {
        return $this->handleRequest(function() {
            return $this->client->get('/notifications', [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getNotificationWithLimit(int $limit)
    {
        return $this->handleRequest(function() use($limit) {
            return $this->client->get('/notifications/' . $limit, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function markAllNotificationRead()
    {
        return $this->handleRequest(function() {
            return $this->client->put('/notifications/markAllRead', [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function markNotificationRead(int $notifID)
    {
        return $this->handleRequest(function() use($notifID) {
            return $this->client->put('/notifications/' . $notifID, [
                'headers'   => $this->getHeader()
            ]);
        });
    }
}
