<?php

namespace App\Service;

class WebSocketApiService extends BaseApiService
{
    public function fetchWSTicket()
    {
        return $this->handleRequest(function() {
            return $this->client->get('/ws-ticket', [
                'headers'   => $this->getHeader()
            ]);
        });
    }
}
