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
                                      'message' => $response['message'],
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
            return redirect()->to('')
                             ->with('error', $response['message']);
        }

        return $response['data'];
    }

    public function isUserLikedThisPost(int $postID)
    {
        $result = $this->model->select('COUNT(*) as count')
                              ->where('user_id', session('uID'))
                              ->where('post_id', $postID)
                              ->find();
        
        $count = (int) $result[0]['count'];
        if($count > 0) {
            return true;
        } else {
            return false;
        }
    }
}
