<?php

use Phalcon\Mvc\Controller;
use MongoDB\BSON\ObjectID;

class ProductController extends Controller
{
    /**
     * productList function
     * list all products
     * @return void
     */
    public function productListAction()
    {
        $Products = new Products($this->mongo, 'store', 'products');
        $this->view->products = $Products->find();
    }

    /**
     * addProduct function
     * add a new product
     * @return void
     */
    public function addProductAction()
    {
        $newProduct = new Products($this->mongo, 'store', 'products');
        $myescaper = new \App\Components\Myescaper();
        $myescaper = $myescaper->santize($this->request->get());
        $count = $myescaper['count'];
        $variation_count = $myescaper['variation_count'];

        $additional_field = [];
        $variation_field = [];
        for ($i = 1; $i <= $count; $i++) {
            $label = $myescaper['label' . $i];
            $value = $myescaper['value' . $i];

            unset($myescaper['label' . $i]);
            unset($myescaper['value' . $i]);
            unset($myescaper['count']);
            $additional_field[$label] = $value;
        }
        for ($i = 1; $i <= $variation_count; $i++) {
            $label = $myescaper['variation_label' . $i];
            $value = $myescaper['variation_value' . $i];
            $price = $myescaper['variation_price' . $i];
            unset($myescaper['variation_label' . $i]);
            unset($myescaper['variation_value' . $i]);
            unset($myescaper['variation_price' . $i]);
            unset($myescaper['variation_count']);
            $variation_field[$i] = [
                $label => $value,
                'price' => $price
            ];
        }
        $myescaper += ['variation' => $variation_field];
        $myescaper += ['additional' => $additional_field];


        $newProduct = $newProduct->insertOne(
            $myescaper,
        );
        if ($newProduct->getInsertedCount()) {
            $eventsManager = $this->di->get('EventsManager');
            $eventsManager->fire('notification:createhook', (object)$myescaper);
        }
    }


    /**
     * delete function
     * delete a particular product
     * @return void
     */
    public function deleteAction()
    {
        $product = new Products($this->mongo, 'store', 'products');
        $product = $product->deleteOne(['_id' => new ObjectID((string) $this->request->get('id'))]);
    }

    /**
     * editProduct function
     * edit and update a particular product
     * @return void
     */
    public function editProductAction()
    {

        // $this->view->t = $this->cache->get('en');

        $collection = new Products($this->mongo, 'store', 'products');
        $product = $collection->findOne(["_id" =>  new ObjectID((string) $this->request->get('id'))]);

        // $product = Products::find($this->request->get('id'));
        $this->view->product = $product;
        if ($this->request->isPost('Update Product')) {
            // die('hello');
            $myescaper = new App\Components\Myescaper();
            $myescaper = $myescaper->santize($this->request->getPost());
            $updateResult = $collection->replaceOne(
                ['_id' => new ObjectID((string) $this->request->get('id'))],
                [
                    'product_name' => $myescaper['product_name'],
                    'description' => $myescaper['description'],
                    'tags' => $myescaper['tags'],
                    'price' => $myescaper['price'],
                    'stocks' => $myescaper['stocks']
                ],
            );

            if ($updateResult->getModifiedCount()) {
                $eventsManager = $this->di->get('EventsManager');
                $eventsManager->fire('notification:updatehook', (object)$myescaper);
            }
        }
    }
}
