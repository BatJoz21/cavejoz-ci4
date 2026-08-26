<?php

namespace App\Service;

use GuzzleHttp\Client;

class BaseApiService {
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri'      => env('api.baseURL'),
            'timeout'       => 10
        ]);
    }

    protected function getHeader()
    {
        return [
            'Authorization' => 'Bearer ' . session('access_token'),
            'Accept'        => 'application/json'
        ];
    }

    protected function handleRequest(callable $callback)
    {
        try {
            $response = $callback();

            return [
                'success'   => true,
                'data'      => json_decode($response->getBody(), true)
            ];
        } catch(\GuzzleHttp\Exception\ClientException $e) {
            $status = $e->getResponse()->getStatusCode();
            if($status != 401) {
                return [
                    'success'   => false,
                    'message'   => $e->getMessage()
                ];
            }

            $isRefreshed = $this->refreshAccessToken();
            if(!$isRefreshed) {
                return [
                    'success'   => false,
                    'message'   => 'Failed to refresh session'
                ];
            }

            try {
                $response = $callback();

                return [
                    'success'   => true,
                    'data'      => json_decode($response->getBody(), true)
                ];
            } catch(\Throwable $e) {
                return [
                    'success'   => false,
                    'message'   => $e->getMessage()
                ];
            }
        } catch(\Throwable $e) {
            return [
                'success'   => false,
                'message'   => $e->getMessage()
            ];
        }
    }

    private function refreshAccessToken()
    {
        try {
            $response = $this->client->post('/refresh', [
                'json'      => [
                    'refresh_token' => session('refresh_token')
                ]
            ]);

            $body = json_decode($response->getBody(), true);
            session()->set('access_token', $body['access_token']);
            return true;
        } catch(\Throwable $e) {
            return false;
        }
    }
}