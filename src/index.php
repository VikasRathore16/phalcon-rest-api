<?php

require('./library/vendor/autoload.php');

use Phalcon\Mvc\Micro;
use Phalcon\Loader;

$loader = new Loader();
$loader->registerNamespaces(
    [
        'Api\Handler' => './handlers'
    ]
);

$loader->register();

$app = new Micro();

$products = new Api\Handler\Products();


$app->get(
    '/products/search/{keyword}',
    [
        $products,
        'search'
    ]
);

$app->get(
    '/products/get',
    [
        $products,
        'get'
    ]
);

$app->get(
    '/products/get/{per_page}',
    [
        $products,
        'get'
    ]
);

$app->get(
    '/products/get/{per_page}/{page}',
    [
        $products,
        'get'
    ]
);

$app->handle(
    $_SERVER["REQUEST_URI"]
);
