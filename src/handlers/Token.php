<?php

namespace Api\Handler;

use MongoDB\Client;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Api\Helper\Acl;

class Token
{
    public $collection;

    public function __construct()
    {
        $Client = new Client("mongodb+srv://cluster0.gbzl3.mongodb.net/myFirstDatabase", array("username" => 'root', "password" => "Vikas@1998"));
        $this->collection = $Client->store->products;
    }

    public function generateToken($role)
    {
        header('Content-Type: application/json; charset=utf-8');
        $payload = array(
            "role" => $role,
        );
        //ecoding array
        $key = "example_key";
        $jwt = JWT::encode($payload, $key, 'HS256');
        $response = [
            'role' => $role,
            'token' => $jwt
        ];

        return json_encode($response);
    }
}
