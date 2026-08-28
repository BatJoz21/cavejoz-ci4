<?php

namespace App\Service;

class UserApiService extends BaseApiService
{
    public function getMyProfile()
    {
        return $this->handleRequest(function() {
            return $this->client->get('/profile', [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getUserProfile(string $username)
    {
        return $this->handleRequest(function() use($username) {
            return $this->client->get('/profile/' . $username, [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getUserAvatarFileName()
    {
        return $this->handleRequest(function() {
            return $this->client->get('/user-avatar', [
                'headers'   => $this->getHeader()
            ]);
        });
    }

    public function getUserAvatar(string $filename)
    {
        return $this->client->get('/avatar/' . $filename, [
            'headers'   => $this->getHeader()
        ]);
    }

    public function editUserData(array $data, ?\CodeIgniter\HTTP\Files\UploadedFile $avatar = null)
    {
        $multipart = [
            [
                'name'      => 'username',
                'contents'  => $data['username']
            ],
            [
                'name'      => 'full_name',
                'contents'  => $data['full_name']
            ],
            [
                'name'      => 'bio',
                'contents'  => $data['bio']
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
            return $this->client->put('/profile', [
                'headers'   => $this->getHeader(),
                'multipart' => $multipart
            ]);
        });
    }
}
