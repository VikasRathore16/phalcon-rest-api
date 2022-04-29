<?php

declare(strict_types=1);

require '../library/vendor/autoload.php';
define('BASE_PATH', __DIR__);

use Phalcon\Loader;
use Phalcon\Mvc\Micro;

$loader = new Loader();
$loader->registerNamespaces(
    [
        'Api\Handler' => BASE_PATH . '/handlers',
    ]
);

$loader->register();


$app = new Micro();

$products = new Api\Handler\Products();
$token = new Api\Handler\Token();
$acl = new Api\Handler\Acl();
$user = new Api\Handler\User();
$orders = new Api\Handler\Orders();


//-------------------------------------------------ROUTES STARTS-----------------------------------------------------

//-------------------------------------------------acl routes-----------------------------------------------------
$app->get(
    '/api/acl',
    [
        $acl,
        'buildAcl',
    ]
);



//-------------------------------------------------bearer routes-----------------------------------------------------


$app->get(
    '/api/getbearer',
    [
        $token,
        'getBearerToken',
    ]
);

$app->get(
    '/api/bearer/{role}',
    [
        $token,
        'generateToken',
    ]
);

//-------------------------------------------------products routes-----------------------------------------------------


$app->get(
    '/api/products/search/{keyword}',
    [
        $products,
        'search',
    ]
);

$app->get(
    '/api/products/get',
    [
        $products,
        'get',
    ]
);

$app->get(
    '/api/products/get/{per_page}',
    [
        $products,
        'pages',
    ]
);

$app->get(
    '/api/products/get/{per_page}/{page}',
    [
        $products,
        'perPage'
    ]
);

//------------------------------------user routes---------------------------------------
$app->post(
    '/api/user/addUser',
    [
        $user,
        'addUser',
    ]
);


//-----------------------------------user routes------------------------------
$app->post(
    '/api/order/create',
    [
        $orders,
        'create',
    ]
);

$app->put(
    '/api/order/update',
    [
        $orders,
        'update',
    ]
);

//-------------------------------------ROUTES ENDS------------------------------------
$app->before(
    static function () use ($app): void {
        $url = explode('/', $_SERVER['REQUEST_URI']);

        if ($url[2] === 'bearer' || $url[2] === 'acl') {
            return;
        }
        $key = $app->request->getQuery('bearer');
        if (isset($key)) {
            $middleware = new \Api\Handler\Middleware();
            $middleware->check($key);
        } else {
            die("Please Provide Token");
        }
    }
);

$app->notFound(
    static function () use ($app): void {
        $app->response->setStatusCode(404, 'Not Found');
        $app->response->sendHeaders();
        $message = 'Nothing to see here. Move along....';
        $app->response->setContent($message);
        $app->response->send();
    }
);


$app->handle(
    $_SERVER["REQUEST_URI"]
);
