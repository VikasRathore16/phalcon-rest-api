<?php

use Phalcon\Mvc\Controller;
use MongoDB\BSON\ObjectID;
use MongoDB\Client;

class AdminController extends Controller
{
    public function indexAction()
    {
    }
    public function dashboardAction()
    {
      
        $Client = new Client("mongodb+srv://cluster0.gbzl3.mongodb.net/myFirstDatabase", array("username" => 'root', "password" => "Vikas@1998"));
        $collection = $Client->store->orders;
        $orders = $collection->find();
        $orders = json_decode(json_encode($orders->toArray(), true));
        $this->view->orders = $orders;
    }
}
