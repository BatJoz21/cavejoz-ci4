<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\PostApiService;

class Posts extends BaseController
{
    private PostApiService $api;
    private Likes $like;

    public function __construct()
    {
        $this->api = new PostApiService();
        $this->like = new Likes();
    }

    public function new()
    {
        // Get current path for toggle add post button visibility
        $currentPath = uri_string();

        return view('Posts/create', ['currentPath' => $currentPath]);
    }

    public function create()
    {
        // User Input Validation
        $rules = config('Validation')->newPost;
        if(!$this->validate($rules)) {
            return redirect()->back()
                             ->with('errors', $this->validator->getErrors())
                             ->withInput();
        }

        // Get uploaded file
        $content = $this->request->getFile('content');

        // Call API and handle it's response
        $response = $this->api->createNewPost([
            'visibility'    => $this->request->getPost('visibility'),
            'caption'       => $this->request->getPost('caption')
        ], $content);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', $response['message'])
                             ->withInput();
        }

        return redirect()->to('/profile')
                         ->with('message', $response['data']['message']);
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

    public function edit(int $postID)
    {
        // Call the API and handle it's response
        $response = $this->api->getPostByID($postID);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', $response['message'])
                             ->withInput();
        }

        return view('Posts/edit', ['post' => $response['data']]);
    }

    public function update(int $postID)
    {
        // Get uploaded file
        $content = $this->request->getFile('content');

        // Call the API and handle it's response
        $response = $this->api->updatePost([
            'id'            => $postID,
            'visibility'    => $this->request->getPost('visibility'),
            'caption'       => $this->request->getPost('caption')
        ], $content);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', $response['message'])
                             ->withInput();
        }

        return redirect()->to('/profile')
                         ->with('message', $response['data']['message']);
    }

    public function delete(int $postID)
    {
        // Call the API and handle it's response
        $response = $this->api->deletePost($postID);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', $response['message'])
                             ->withInput();
        }

        return redirect()->to('/profile')
                         ->with('message', $response['data']['message']);
    }

    public function loadMorePosts(int $id, int $page)
    {
        // Call the API
        $response = $this->api->getUserPosts($id, $page);
        if(!$response['success']) {
            return $this->response->setStatusCode(500)->setJSON([
                'success'   => false,
                'message'   => $response['message']
            ]);
        }

        // Get post's like data
        $posts = $responsePost['data'] ?? [];
        foreach($posts as &$post) {
            $post['liked_by_me'] = $this->like->isUserLikedThisPost($post['id']);
            $post['like_count'] = $this->like->getTotalLikesOfThisPost($post['id']);
        }
        unset($post);

        // return data as JSON
        return $this->response->setJSON([
            'success'   => true,
            'posts'     => $posts
        ]);
    }
}
