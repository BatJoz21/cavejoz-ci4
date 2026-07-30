<?php

namespace App\Service;

class UserApiService extends BaseApiService
{
    public function getMyProfile()
    {
        return $this->handleRequest(function() {
            return $this->client->get('/profile', [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getUserProfile(string $username)
    {
        return $this->handleRequest(function() use($username) {
            return $this->client->get('/profile/' . $username, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getUserAvatar(string $filename)
    {
        return $this->client->get('/avatar/' . $filename, [
            'headers'   => $this->getHeader()
        ]);
    }
}
