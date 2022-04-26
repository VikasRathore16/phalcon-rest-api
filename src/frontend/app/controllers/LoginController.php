<?php



use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use MongoDB\BSON\ObjectID;
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
    public function indexAction()
    {
        // $locale = $this->request->has('locale') ? $this->request->get('locale') : 'en';
        // $this->view->t = $this->cache->get($locale);
        $request = new Request();
        if ($request->isPost()) {
            $myescaper = new \App\Components\Myescaper();
            $request = $myescaper->santize($request->getPost());
            $user = $this->checkLogin($request);
            if ($user[0]->role == 'admin') {
                $bearer = $user[0]->jwt;
                $this->response->redirect("http://localhost:8080/frontend/admin/dashboard?bearer=$bearer");
            }
        }
    }


    public function logoutAction()
    {
        $this->session->destroy();
        $this->response->redirect('http://localhost:8080/');
    }


    /**
     * checkLogin function
     * check user token and decode it for user role
     * @param [array] $request
     * @return void
     */
    protected function checkLogin($request)
    {
        $Client = new Client("mongodb+srv://cluster0.gbzl3.mongodb.net/myFirstDatabase", array("username" => 'root', "password" => "Vikas@1998"));
        $collection = $Client->store->users;
   
        $user = $collection->find([
            'email' => $request['email'],
            'password' => $request['password']

        ]);
        $objects = json_decode(json_encode($user->toArray(), true));

        return ($objects);

        // die();

        // return $user;


        // unset($this->session->user);
        // try {
        //     $key = "example_key";
        //     $users = new Users($this->mongo, 'store', 'users');
        //     $users = $users->find();
        //     foreach ($users as $user) {
        //         $decoded = JWT::decode($user->jwt, new Key($key, 'HS256'));
        //         $decoded_array = (array) $decoded;
        //         $email = $decoded_array['email'];
        //         $password = $decoded_array['password'];
        //         if ($email == $request['email'] && $password == $request['password']) {
        //             $role = $decoded_array['role'];
        //             $username = $decoded_array['username'];
        //             $this->session->set('role', $role);
        //             $this->session->set('username', $username);
        //             break;
        //         }
        //     }
        // } catch (\Exception $e) {
        //     echo "Try with accessable token please !";
        //     die;
        // }
        // return $this->session->get('username');
    }
}
