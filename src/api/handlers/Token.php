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

    function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    /**
     * get access token from header
     * */
    function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}
