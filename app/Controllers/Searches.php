<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Searches extends BaseController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function search()
    {
        // Get user's input
        $search = $this->request->getGet('pageSearchInput');
        $searchResults = [];

        if(!empty($search)) {
            // Get data from database
            $searchResults = $this->userModel->select('id, username, full_name, avatar_url')
                                             ->like('username', $search)
                                             ->findAll();
        }

        return view('Home/search', [
            'search'        => $search,
            'searchResults' => $searchResults
        ]);
    }
}
