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

        // Get current page
        $page = $this->request->getGet('page') ?? '1';
        $page = (int) $page;
        $totalPage = 1;
        $resultPerPage = 10;

        if(!empty($search)) {
            // Get total search result and count pagination
            $totalData = $this->userModel->like('username', $search)
                                         ->countAllResults();
            $totalPage = ceil($totalData / $resultPerPage);
            $offset = $resultPerPage * ($page - 1);

            // Get data from database
            $searchResults = $this->userModel->select('id, username, full_name, avatar_url')
                                             ->like('username', $search)
                                             ->findAll($resultPerPage, $offset);
        }

        return view('Home/search', [
            'search'        => $search,
            'searchResults' => $searchResults,
            'currentPage'   => $page,
            'totalPage'     => $totalPage
        ]);
    }
}
