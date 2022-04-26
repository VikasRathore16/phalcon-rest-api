<?php

use Phalcon\Mvc\Controller;
use MongoDB\Client;

class IndexController extends Controller
{
    public function indexAction()
    {
        // die('ads');
        if ($this->request->has('events')) {
            $Client = new Client("mongodb+srv://cluster0.gbzl3.mongodb.net/myFirstDatabase", array("username" => 'root', "password" => "Vikas@1998"));
            $collection = $Client->store->webhook;
            $collection->insertOne($this->request->get());
        }
    }
}
