<?php

namespace App\Service;

class CommentApiService extends BaseApiService
{
    public function addNewComment(int $postID, string $content)
    {
        return $this->handleRequest(function() use($postID, $content) {
            return $this->client->post('/posts/' . $postID . '/comment', [
                'headers'   => $this->getHeader(),
                'json'      => ['content' => $content]
            ]);
        });
    }

    public function getCommentsByPostID(int $postID, int $page = 1)
    {
        return $this->handleRequest(function() use($postID, $page) {
            return $this->client->get('/posts/' . $postID . '/comment?page=' . $page, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function deleteComment(int $postID, int $commentID)
    {
        return $this->handleRequest(function() use($postID, $commentID) {
            return $this->client->delete('/posts/' . $postID . '/comment/' . $commentID, [
                'headers'   => $this->getHeader()
            ]);
        });
    }
}
