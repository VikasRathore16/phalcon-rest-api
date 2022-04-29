<?php

declare(strict_types=1);

namespace Api\Handler;

use MongoDB\Client;
use Phalcon\Http\Request;

class User
{
    public object $collection;

    public function __construct()
    {
        $Client = new Client(
            "mongodb+srv://cluster0.gbzl3.mongodb.net/myFirstDatabase",
            [
                "username" => 'root',
                "password" => "Vikas@1998"
            ]
        );
        $this->collection = $Client->store->users;
    }

    public function addUser(): ?int
    {
        $request = new Request();
        $token = new Token();
        $postArray = [
            "username" => $request->getPost('username'),
            "email" => $request->getPost('email'),
            "password" => $request->getPost('password'),
            "role" => $request->getPost('role'),
        ];
        $jwt = $token->generateToken($postArray);
        $jwt = json_decode($jwt);
        $postArray['jwt'] = $jwt->token;

        $user = $this->collection->insertOne(
            $postArray
        );
        return  $user->getInsertedCount();
    }
}
