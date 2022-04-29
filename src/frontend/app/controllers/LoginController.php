<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use MongoDB\Client;

/**
 * LoginContoller class
 */
class LoginController extends Controller
{
    /**
     * index function
     * handle login form request
     * @return void
     */
    public function indexAction(): void
    {

        $request = new Request();
        if ($request->isPost()) {
            $myescaper = new \App\Components\Myescaper();
            $request = $myescaper->santize($request->getPost());
            $user = $this->checkLogin($request);
            if ($user->role === 'admin') {
                $bearer = $user->jwt;
                $this->response->redirect("http://localhost:8080/frontend/admin/dashboard?bearer=${bearer}");
            } else {
                $bearer = $user->jwt;
                $this->response->redirect("http://localhost:8080/frontend/index/index?bearer=${bearer}");
            }
        }
    }


    public function logoutAction(): void
    {
        $this->session->destroy();
        $this->response->redirect('http://localhost:8080/');
    }


    /**
     * checkLogin function
     * check user token and decode it for user role
     * @param (array) $request
     * @return object
     */
    protected function checkLogin($request): ?object
    {
        $Client = new Client("mongodb+srv://cluster0.gbzl3.mongodb.net/myFirstDatabase", array("username" => 'root', "password" => "Vikas@1998"));
        $collection = $Client->store->users;

        $user = $collection->find([
            'email' => $request['email'],
            'password' => $request['password']
        ]);
        $arr = $user->toArray();
        return $arr[0];
    }
}
