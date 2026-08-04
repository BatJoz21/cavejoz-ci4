<?php

namespace App\Service;

class LikeApiService extends BaseApiService
{
    public function toggleLike(int $postID)
    {
        return $this->handleRequest(function() use($postID) {
            return $this->client->post('/posts/' . $postID . '/like', [
                'headers'       => $this->getHeader()
            ]);
        });
    }

    public function getTotalLike(int $postID)
    {
        return $this->handleRequest(function() use($postID) {
            return $this->client->get('/posts/' . $postID . '/like', [
                'headers'       => $this->getHeader()
            ]);
        });
    }
}
