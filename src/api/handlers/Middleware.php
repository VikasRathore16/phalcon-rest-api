<?php

namespace Api\Handler;

use MongoDB\BSON\ObjectID;
use MongoDB\Client;
use Phalcon\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Middleware
{
    public function check($bearer)
    {
        try {
            $key = "example_key";
            $decoded = JWT::decode($bearer, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;
            $role = $decoded_array['role'];
            return $role;
        } catch (\Exception $e) {
            echo "Try with accessable token please !";
            die;
        }
    }
}
