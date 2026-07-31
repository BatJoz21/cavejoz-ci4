<?php

namespace App\Service;

class PostApiService extends BaseApiService
{
    public function getUserPosts(int $id, int $page=1)
    {
        return $this->handleRequest(function() use($id, $page) {
            return $this->client->get('/users/posts/' . $id . '?page=' . $page, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getPostContentImage(string $filename)
    {
        return $this->client->get('/content/image/' . $filename, [
            'headers'   => $this->getHeader()
        ]);
    }

    public function getTotalPostByUId(int $id)
    {
        return $this->handleRequest(function() use($id) {
            return $this->client->get('/users/posts/' . $id . '/total', [
                'headers'   => $this->getHeader()
            ]);
        });
    }
}
