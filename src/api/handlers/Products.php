<?php

namespace Api\Handler;

use MongoDB\Client;

class Products
{
    private object $collection;

    public function __construct()
    {
        $Client = new Client("mongodb+srv://cluster0.gbzl3.mongodb.net/myFirstDatabase", array("username" => 'root', "password" => "Vikas@1998"));
        $this->collection = $Client->store->products;
    }

    public function pages(string $per_page): ?string
    {
        header('Content-Type: application/json; charset=utf-8');

        return json_encode((object) ($this->collection->find([], ['limit' => (int) $per_page])->toArray()));
    }

    public function perPage(string $per_page = "", string $page = ''): ?string
    {
        header('Content-Type: application/json; charset=utf-8');

        $options = [
            "limit" => (int) $per_page,
            "skip" => (int) $page
        ];

        return json_encode((object) ($this->collection->find([], $options)->toArray()));
    }
    public function get(): ?string
    {
        header('Content-Type: application/json; charset=utf-8');
        return json_encode((object) ($this->collection->find()->toArray()));

        // $options = [
        //     "limit" => (int)$per_page,
        //     "skip" => (int)$page
        // ];
        // return ($per_page === "" && $page === '') ? json_encode((object)($this->collection->find()->toArray())) : (($page === '') ? json_encode((object)($this->collection->find([], ['limit' => (int)$per_page])->toArray())) : json_encode((object)($this->collection->find([], $options)->toArray())));
        // return $result;
        // if ($per_page === "" && $page === '') {
        //     return json_encode((object)($this->collection->find()->toArray()));
        // }
        // if ($page === '') {
        //     return json_encode((object)($this->collection->find([], ['limit' => (int)$per_page])->toArray()));
        // }

        // $options = [
        //     "limit" => (int)$per_page,
        //     "skip" => (int)$page
        // ];

        // return json_encode((object)($this->collection->find([], $options)->toArray()));
    }

    public function search(string $keywords = ""): ?string
    {
        $params = array();
        $allKeywords = [];
        if (str_contains($keywords, '%20')) {
            $allKeywords = explode('%20', $keywords);
        }
        foreach ($allKeywords as $values) {
            array_push($params, array('product_name' => $values));
        }

        $keyword = urldecode($keywords);
        array_push($params, array('product_name' => $keyword));
        // print_r($params);
        header('Content-Type: application/json; charset=utf-8');
        return json_encode((object)($this->collection->find(array('$or' => $params))->toArray()));
    }
}
