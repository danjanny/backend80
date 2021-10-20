<?php

namespace Modules\Auth\Controllers;

# use App\Controllers\BaseController;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use Modules\Auth\Models\AuthModel;
use \Firebase\JWT\JWT;

class AuthController extends ResourceController
{

    public function __construct() {
    }

    public function index() {
        // 
    }

    public function detail() {
        // Content-Type:application/json
        // Accept:application/json
        // Authorization:<TOKEN>

        
        $authHeader = $this->request->getHeader("Authorization"); // get http header key 'authorization'
        $token = $authHeader->getValue(); // get JWT token

        var_dump(JWT::decode($token, SERVER_KEY, ["HS256"]));

    }


}
