<?php

namespace App\Service;

class PostApiService extends BaseApiService
{
    public function createNewPost(array $data, ?\CodeIgniter\HTTP\Files\UploadedFile $content = null)
    {
        $multipart = [
            [
                'name'      => 'visibility',
                'contents'  => $data['visibility']
            ],
            [
                'name'      => 'caption',
                'contents'  => $data['caption']
            ]
        ];

        if($content && $content->isValid()) {
            $multipart[] = [
                'name'      => 'content',
                'contents'  => fopen($content->getTempName(), 'r'),
                'filename'  => $content->getClientName()
            ];
        }

        return $this->handleRequest(function() use($multipart) {
            return $this->client->post('/posts', [
                'headers'       => $this->getHeader(),
                'multipart'     => $multipart
            ]);
        });
    }

    public function getFeeds(int $page)
    {
        return $this->handleRequest(function() use($page) {
            return $this->client->get('/feeds?page=' . $page, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getUserPosts(int $id, int $page=1)
    {
        return $this->handleRequest(function() use($id, $page) {
            return $this->client->get('/users/posts/' . $id . '?page=' . $page, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getPostByID(int $postID)
    {
        return $this->handleRequest(function() use($postID) {
            return $this->client->get('/posts/' . $postID, [
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

    public function updatePost(array $data, ?\CodeIgniter\HTTP\Files\UploadedFile $content = null)
    {
        $multipart = [
            [
                'name'      => 'visibility',
                'contents'  => $data['visibility']
            ],
            [
                'name'      => 'caption',
                'contents'  => $data['caption']
            ]
        ];

        if($content && $content->isValid()) {
            $multipart[] = [
                'name'      => 'content',
                'contents'  => fopen($content->getTempName(), 'r'),
                'filename'  => $content->getClientName()
            ];
        }

        return $this->handleRequest(function() use($data, $multipart) {
            return $this->client->put('/posts/' . $data['id'], [
                'headers'       => $this->getHeader(),
                'multipart'     => $multipart
            ]);
        });
    }

    public function deletePost(int $id)
    {
        return $this->handleRequest(function() use($id) {
            return $this->client->delete('/posts/' . $id, [
                'headers'   => $this->getHeader()
            ]);
        });
    }
}
