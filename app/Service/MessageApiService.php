<?php

namespace App\Service;

class MessageApiService extends BaseApiService
{
    public function sendMessageRoute(int $cID, string $message)
    {
        return $this->handleRequest(function() use($cID, $message) {
            return $this->client->post('/dm/' . $cID . '/message', [
                'headers'   => $this->getHeader(),
                'json'      => ['content' => $message]
            ]);
        });
    }

    public function getMessageByCID(int $cID)
    {
        return $this->handleRequest(function() use($cID) {
            return $this->client->get('/dm/' . $cID . '/message', [
                'headers'   => $this->getHeader()
            ]);
        });
    }
}
