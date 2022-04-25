<?php

require('./library/vendor/autoload.php');
define('BASE_PATH', __DIR__);

use Phalcon\Mvc\Micro;
use Phalcon\Loader;

$loader = new Loader();
$loader->registerNamespaces(
    [
        'Api\Handler' => './handlers',
    ]
);

$loader->register();

$app = new Micro();

$products = new Api\Handler\Products();
$token = new Api\Handler\Token();
$acl = new Api\Handler\Acl();

$app->get(
    '/acl',
    [
        $acl,
        'buildAcl'
    ]
);

$app->get(
    '/bearer/{role}',
    [
        $token,
        'generateToken'
    ]
);


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
