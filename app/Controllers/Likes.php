<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LikeModel;
use App\Service\LikeApiService;

class Likes extends BaseController
{
    private LikeApiService $api;
    private LikeModel $model;

    public function __construct()
    {
        $this->api = new LikeApiService();
        $this->model = new LikeModel();
    }

    public function toggleLikeOnPost(int $postID)
    {
        $response = $this->api->toggleLike($postID);
        if(!$response['success']) {
            return $this->response->setStatusCode(400)
                                  ->setJSON([
                                      'success' => false,
                                      'message' => 'failed to like/unlike this post',
                                      'csrf'    => csrf_hash()
                                  ]);
        }

        return $this->response->setJSON([
            'success'   => true,
            'liked'     => $response['data']['liked'],
            'count'     => $response['data']['count'],
            'csrf'      => csrf_hash()
        ]);
    }

    public function getTotalLikesOfThisPost(int $postID)
    {
        $response = $this->api->getTotalLike($postID);
        if(!$response['success']) {
            return 0;
        }

        return $response['data'];
    }

    public function isUserLikedThisPost(int $postID)
    {
        $result = $this->model->where('user_id', session('user')['id'])
                              ->where('post_id', $postID)
                              ->countAllResults() > 0;
        
        return $result;
    }
}
