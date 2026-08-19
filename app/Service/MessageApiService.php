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

    public function getMessageByCID(int $cID, int $cursor=0)
    {
        $url = '/dm/' . $cID . '/message';
        if($cursor > 0) {
            $url = $url . '?cursor=' . $cursor;
        }

        return $this->handleRequest(function() use($url) {
            return $this->client->get($url, [
                'headers'   => $this->getHeader()
            ]);
        });
    }
}
