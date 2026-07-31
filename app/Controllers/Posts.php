<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\PostApiService;

class Posts extends BaseController
{
    private PostApiService $api;

    public function __construct()
    {
        $this->api = new PostApiService();
    }

    public function getPostContentImage(string $filename)
    {
        // Call the API to get the content image
        $response = $this->api->getPostContentImage($filename);
        
        // Get the response's body
        $body = (string) $response->getBody();

        // Set up to show the image
        return $this->response->setStatusCode(200)
                              ->setHeader('Content-Type', $response->getHeaderLine('Content-Type'))
                              ->setHeader('Content-Length', strlen($body))
                              ->setBody($body);
    }

    public function loadMorePosts(int $id, int $page)
    {
        $response = $this->api->getUserPosts($id, $page);
        if(!$response['success']) {
            return $this->response->setStatusCode(500)->setJSON([
                'success'   => false,
                'message'   => $response['message']
            ]);
        }

        return $this->response->setJSON([
            'success'   => true,
            'posts'     => $response['data']
        ]);
    }
}
