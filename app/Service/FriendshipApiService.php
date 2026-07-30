<?php

namespace App\Service;

class FriendshipApiService extends BaseApiService
{
    public function getTotalFriendByUId(int $uID)
    {
        return $this->handleRequest(function() use($uID) {
            return $this->client->get('/friends/' . $uID . '/total', [
                'headers'   => $this->getHeader()
            ]);
        });
    }
}
