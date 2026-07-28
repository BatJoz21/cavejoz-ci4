<?php

namespace App\Service;

class AuthApiService extends BaseApiService
{
    public function register(array $data, ?\CodeIgniter\HTTP\Files\UploadedFile $avatar = null)
    {
        $multipart = [
            [
                'name'      => 'username',
                'contents'  => $data['username']
            ],
            [
                'name'      => 'email',
                'contents'  => $data['email']
            ],
            [
                'name'      => 'password',
                'contents'  => $data['password']
            ],
            [
                'name'      => 'full_name',
                'contents'  => $data['full_name']
            ]
        ];

        if($avatar && $avatar->isValid()) {
            $multipart[] = [
                'name'      => 'avatar',
                'contents'  => fopen($avatar->getTempName(), 'r'),
                'filename'  => $avatar->getClientName()
            ];
        }

        return $this->handleRequest(function() use($multipart) {
            return $this->client->post('/register', [
                'multipart' => $multipart
            ]);
        });
    }

    public function login(array $data)
    {
        return $this->handleRequest(function() use($data) {
            return $this->client->post('/login', [
                'json'  => $data
            ]);
        });
    }

    public function logout()
    {
        if(empty(session('refresh_token'))) {
            return [];
        }

        return $this->handleRequest(function() {
            return $this->client->post('/logout', [
                'json'  => [
                    'refresh_token' => session('refresh_token')
                ]
            ]);
        });
    }
}
