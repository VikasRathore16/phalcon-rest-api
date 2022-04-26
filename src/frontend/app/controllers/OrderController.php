<?php

use Phalcon\Mvc\Controller;
use App\Components\Mycurl;
use MongoDB\BSON\ObjectID;
use MongoDB\Client;

class OrderController extends Controller
{
    public function indexAction()
    {
        $mycurl = new Mycurl();
        $bearer = $this->request->get('bearer');
        $products = $mycurl->find('GET', '/products/get?bearer=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJyb2xlIjoiYWRtaW4ifQ.Sts-82E95LpNwdzaDXWChp4m-yvVLS0tQcNMTxvFrvQ');
        $this->view->products = $products;
        if ($this->request->has('id')) {
            $Client = new Client("mongodb+srv://cluster0.gbzl3.mongodb.net/myFirstDatabase", array("username" => 'root', "password" => "Vikas@1998"));
            $product = $Client->store->product;
            $product = $product->findOne(['_id' => new ObjectID((string) $this->request->get('id'))]);
            echo json_encode($product);
            die();
        }
        if ($this->request->has('token')) {
            $order = $mycurl->find('POST', "/order/create?bearer=$bearer", '', $this->request->get());
            // $this->view->response = $response->token;
        }
    }
}
