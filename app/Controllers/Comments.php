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
        $response = $this->api->addNewComment($postID, $this->request->getPost('content'));
        if(!$response['success']) {
            return redirect()->back()
                             ->with('error', $response['message']);
        }

        return redirect()->back()
                         ->with('message', $response['data']['message']);
    }

    public function getAllCommentsOfAPost(int $postID, int $page)
    {
        $response = $this->api->getCommentsByPostID($postID, $page);
        if(!$response['success']) {
            return [];
        }

        return $response['data'];
    }

    public function getTotalCommentOfPost(int $postID)
    {
        $result = $this->model->where('post_id', $postID)
                              ->countAllResults();

        return $result;
    }
}
