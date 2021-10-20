<?php

namespace App\Controllers;

use App\Libraries\Export\Excel;
use App\Controllers\BaseController;
use \Firebase\JWT\JWT;

class Home extends BaseController
{
    public function __construct() {
        // parent::__construct();
    }

    public function index()
    {
        return view('welcome_message');
    }

    public function hello() {
        $key = "example_key";
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000
        );

        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($payload, $key);
        echo $jwt;
    }
}
