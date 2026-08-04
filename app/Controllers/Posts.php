<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Service\PostApiService;

class Posts extends BaseController
{
    private PostApiService $api;
    private Likes $like;
    private Comments $comment;

    public function __construct()
    {
        $this->api = new PostApiService();
        $this->like = new Likes();
        $this->comment = new Comments();
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

    public function view(int $postID)
    {
        // Get requested page number
        $currentPage = $this->request->getGet('page') ?? '1';

        // Call the API to get user's posts
        $responsePost = $this->api->getPostByID($postID);
        if(!$responsePost['success']) {
            return redirect()->to('/')
                             ->with('error', 'Failed to get post data: ' . $responsePost['message']);
        }

        // Get post's like and comments data
        $post = $responsePost['data'] ?? [];
        $postComments = [];
        if(!empty($post)) {
            $post['liked_by_me'] = $this->like->isUserLikedThisPost($post['id']);
            $post['like_count'] = $this->like->getTotalLikesOfThisPost($post['id']);
            $post['comment_count'] = $this->comment->getTotalCommentOfPost($post['id']);

            $postComments = $this->comment->getAllCommentsOfAPost($post['id'], $currentPage);
        }

        $totalPage = ceil($postComments['total'] / 10);

        return view('Comments/index', [
            'post'          => $post,
            'comments'      => $postComments['comments'],
            'currentPage'   => (int) $currentPage,
            'totalPage'     => $totalPage
        ]);
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

    public function getTotalPostOfUser(int $uID)
    {
        $response = $this->api->getTotalPostByUId($uID);
        if(!$response['success']) {
            return 0;
        }

        return $response['data'];
    }

    public function loadPostsDataForPostCard(int $id)
    {
        // Call the API to get user's posts
        $responsePost = $this->api->getUserPosts($id);
        if(!$responsePost['success']) {
            return redirect()->to('/')
                             ->with('error', 'Failed to get post data: ' . $responsePost['message']);
        }

        // Get post's like and comments data
        $posts = $responsePost['data'] ?? [];
        foreach($posts as &$post) {
            $post['liked_by_me'] = $this->like->isUserLikedThisPost($post['id']);
            $post['like_count'] = $this->like->getTotalLikesOfThisPost($post['id']);
            $post['comment_count'] = $this->comment->getTotalCommentOfPost($post['id']);
        }
        unset($post);

        return $posts;
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
            $post['comment_count'] = $this->comment->getTotalCommentOfPost($post['id']);
        }
        unset($post);

        // return data as JSON
        return $this->response->setJSON([
            'success'   => true,
            'posts'     => $posts
        ]);
    }
}
