<?php

namespace Api\Handler;

use MongoDB\Client;
use Firebase\JWT\JWT;
use Api\Handler\Token;
use Phalcon\Http\Request;

class User
{
    public $collection;

    public function __construct()
    {
        $Client = new Client("mongodb+srv://cluster0.gbzl3.mongodb.net/myFirstDatabase", array("username" => 'root', "password" => "Vikas@1998"));
        $this->collection = $Client->store->users;
    }


    public function index()
    {

        $this->view->users = $this->collection->find();
    }


    public function addUser()
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
        $postArray['jwt'] = (json_decode($jwt))->token;

        $user =  $this->collection->insertOne(
            $postArray
        );
        $success =  $user->getInsertedCount();
        return $success;
    }
}
