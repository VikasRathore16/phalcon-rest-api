<?php

namespace Api\Handler;

use MongoDB\BSON\ObjectID;
use MongoDB\Client;
use Phalcon\Http\Request;

class Orders
{
    public object $collection;

    public function __construct()
    {
        $Client = new Client("mongodb+srv://cluster0.gbzl3.mongodb.net/myFirstDatabase", array("username" => 'root', "password" => "Vikas@1998"));
        $this->collection = $Client->store->orders;
    }

    public function create(): void
    {
        $request = new Request();
        // $token = new Token();
        $postArray = [
            "customer_name" => $request->getPost('customer_name'),
            "customer_address" => $request->getPost('customer_address'),
            "zipcode" => $request->getPost('zipcode'),
            "products" => $request->getPost('products'),
            "quantity" => $request->getPost('quantity'),
            'created_date' => date('Y-m-d')
        ];
        // $jwt = $token->generateToken($postArray);
        // $postArray['jwt'] = (json_decode($jwt))->token;

        $this->collection->insertOne(
            $postArray
        );
    }

    public function update(): ?string
    {
        $request = new Request();
        $this->collection->updateOne(
            ['_id' => new ObjectID((string) $request->get('id'))],
            [
                '$set' => [
                    'Status' => $request->get('status'),
                    'Status_change_date' => date('Y-m-d')
                ]
            ]
        );
        return 'Success';
    }
}
