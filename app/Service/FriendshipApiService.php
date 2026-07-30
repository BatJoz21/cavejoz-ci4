<?php

namespace App\Service;

class FriendshipApiService extends BaseApiService
{
    public function addFriend(string $addresseID)
    {
        return $this->handleRequest(function() use($addresseID) {
            return $this->client->post('/friends', [
                'headers'   => $this->getHeader(),
                'json'      => ['addressee_id' => $addresseID]
            ]);
        });
    }

    public function blockUser(string $addresseID)
    {
        return $this->handleRequest(function() use($addresseID) {
            return $this->client->post('/block', [
                'headers'   => $this->getHeader(),
                'json'      => ['addressee_id' => $addresseID]
            ]);
        });
    }

    public function getPendingFriendList()
    {
        return $this->handleRequest(function() {
            return $this->client->get('/friends/pending' , [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getFriendsList()
    {
        return $this->handleRequest(function() {
            return $this->client->get('/friends', [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getBlockedList()
    {
        return $this->handleRequest(function() {
            return $this->client->get('/block', [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function acceptFriend(int $frID)
    {
        return $this->handleRequest(function() use($frID) {
            return $this->client->put('/friends/pending/' . $frID, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function rejectOrRemoveFriend(int $frID)
    {
        return $this->handleRequest(function() use($frID) {
            return $this->client->delete('/friends/delete/' . $frID, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getTotalFriendByUId(int $uID)
    {
        return $this->handleRequest(function() use($uID) {
            return $this->client->get('/friends/' . $uID . '/total', [
                'headers'   => $this->getHeader()
            ]);
        });
    }
}
