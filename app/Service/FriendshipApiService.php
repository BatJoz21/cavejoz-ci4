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

    public function getPendingFriendList(int $page)
    {
        return $this->handleRequest(function() use($page) {
            return $this->client->get('/friends/pending?page=' . $page, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getFriendsList(int $page)
    {
        return $this->handleRequest(function() use($page) {
            return $this->client->get('/friends?page=' . $page, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getBlockedList(int $page)
    {
        return $this->handleRequest(function() use($page) {
            return $this->client->get('/block?page=' . $page, [
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

    public function getFriendshipStatus(int $targetUID)
    {
        return $this->handleRequest(function() use($targetUID) {
            return $this->client->get('/friends/status/'. $targetUID, [
                "headers"   => $this->getHeader()
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
