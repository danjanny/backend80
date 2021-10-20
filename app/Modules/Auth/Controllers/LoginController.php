<?php

namespace Modules\Auth\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Exception;
use Modules\Auth\Models\AuthModel;
use \Firebase\JWT\JWT;

class LoginController extends ResourceController
{

    const KEY = 'auth-login-server';

    private $authModel;

    public function __construct() {
        $this->authModel = new AuthModel();
    }

    public function index()
    {
        //
    }

    /**
     * Login
     * @param $username, $password
     * @return [type] [description]
     */
    public function login() {
        $response = [];
        $params = $this->request->getPost();

        // var_dump($params); die();

        try {
            // get user data by username / email && password
            $userDataResponse = $this->authModel->getUser($params);

            if($userDataResponse['status'] == 'error') {
                throw new Exception($userDataResponse['message'], 1);
            }

            // create JWT 
            $iat = time(); // current timestamp value
            $nbf = $iat + 10; // api active after 10 sec, prevent robot etc
            $exp = $iat + 3600; // 1 hour session

            $payload = array(
                "iss" => "The_issuer",
                "aud" => "The_Aud",
                "iat" => $iat, // issued at
                "nbf" => $nbf, //not before in seconds
                "exp" => $exp, // expire time in seconds
                "data" => $userDataResponse['data']
            );

            $token = JWT::encode($payload, SERVER_KEY); // encrypt key

            $statusCode = 200;
            $response = [
                'status' => 'ok',
                'message' => 'User logged In Successfully',
                'token' => $token
            ];

        } catch(Exception $e) {
            $statusCode = 400;
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];            
        }

        return $this->respond($response, $statusCode);

    }
}
