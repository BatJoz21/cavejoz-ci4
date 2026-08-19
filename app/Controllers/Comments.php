<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CommentModel;
use App\Service\CommentApiService;

class Comments extends BaseController
{
    private CommentModel $model;
    private CommentApiService $api;

    public function __construct()
    {
        $this->model = new CommentModel();
        $this->api = new CommentApiService();
    }

    public function create(int $postID)
    {
        // Call API and handle the response
        $response = $this->api->addNewComment($postID, $this->request->getPost('content'));
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Failed to add comment');
        }

        return redirect()->back()
                         ->with('message', $response['data']['message']);
    }

    public function getAllCommentsOfAPost(int $postID, int $page)
    {
        // Call API and handle the response
        $response = $this->api->getCommentsByPostID($postID, $page);
        if(!$response['success']) {
            return [];
        }

        return $response['data'];
    }

    public function getTotalCommentOfPost(int $postID)
    {
        // Call API and handle the response
        $result = $this->model->where('post_id', $postID)
                              ->countAllResults();

        return $result;
    }

    public function deleteComment(int $postID, int $commentID)
    {
        // Call API and handle the response
        $response = $this->api->deleteComment($postID, $commentID);
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', 'Failed to delete comment');
        }

        return redirect()->back()
                         ->with('message', $response['data']['message']);
    }
}
