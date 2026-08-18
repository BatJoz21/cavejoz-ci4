<?php

namespace App\Service;

class ConversationApiService extends BaseApiService
{
    public function createConversation(string $frID)
    {
        $multipart = [
            [
                'name'      => 'friendID',
                'contents'  => $frID
            ]
        ];

        return $this->handleRequest(function() use($multipart) {
            return $this->client->post('/dm', [
                'headers'   => $this->getHeader(),
                'multipart' => $multipart
            ]);
        });
    }

    public function getConversationID(int $frUID)
    {
        return $this->handleRequest(function() use($frUID) {
            return $this->client->get('/dmID?friendID=' . $frUID, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getConversations(int $page=1)
    {
        return $this->handleRequest(function() use($page) {
            return $this->client->get('/dm?page=' . $page, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getConversation(int $cID)
    {
        return $this->handleRequest(function() use($cID) {
            return $this->client->get('/dm/' . $cID, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function recordReadMessage(int $cID)
    {
        return $this->handleRequest(function() use($cID) {
            return $this->client->put('/dm/' . $cID . '/read', [
                'headers'   => $this->getHeader()
            ]);
        });
    }
}
