<?php

namespace App\Services;

use GuzzleHttp\Client;

class WebService
{
    public function __invoke()
    {
        $client = new Client();
        try {
            $res = $client->request('POST', 'https://server.test/api/v1/share_results',
                [
                    \GuzzleHttp\RequestOptions::JSON => [
                        'foo' => 'bar'
                    ],
                    'verify' => false
                ]);
            echo $res->getBody();
//        // 200
//        echo $res->getHeader('content-type');
//        // 'application/json; charset=utf8'
//        echo $res->getBody();
            // {"type":"User"...'
        } catch (\Throwable $e){
            echo $e;
        }
    }
}
