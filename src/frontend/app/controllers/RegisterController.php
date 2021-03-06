<?php

use Phalcon\Mvc\Controller;
use App\Components\Mycurl;

class RegisterController extends Controller
{
    public function indexAction(): void
    {
        if ($this->request->has('role')) {
            $role = $this->request->getPost('role');
            $mycurl = new Mycurl();
            $response = $mycurl->find('GET', "/bearer/${role}");
            $mycurl->find('POST', "/user/addUser?bearer={$response->token}", '', $this->request->getPost());
            $this->view->response = $response->token;
        }
    }
}
