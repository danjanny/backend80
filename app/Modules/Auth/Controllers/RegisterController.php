<?php

namespace Modules\Auth\Controllers;

use Modules\Auth\Models\AuthModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use Modules\Auth\Library\EmailVerification;

class RegisterController extends ResourceController
{

    private $authModel;

    public function __construct() {
        $this->authModel = new AuthModel();
    }

    public function index()
    {
        //
    }

    public function sendEmail()
    {
        var_dump($mailer->sendEmail() );
    }    

    /**
     * User Registration
     * @param $username, $email, $full_nm, $password
     * @return [type] [description]
     */
    public function register() {
        $response = [];
        $statusCode = null;
        $params = $this->request->getPost();

        try {
            foreach($params as $k => $v) {
                if($v == "" || $v == null) {
                    throw new Exception("Error : No value in key params", 1);
                }
            }

            // check username / email sudah ada
            $userDataResponse = $this->authModel->checkUsername($params);

            // status error
            if($userDataResponse['status'] == 'error') {
                throw new Exception($userDataResponse['message'], 1);
            }

            // status ok
            if($userDataResponse['status_detail'] == 'no_user_data') {
                // 1. insert new row of user to user_app table
                $addUserResponse = $this->authModel->addUser($params);

                if($addUserResponse['status'] == 'error') {
                    throw new Exception($addUserResponse['message'], 1);
                }

                // 2. Send verification code to user email / SMS / whatsapp
                $mailer = new EmailVerification($addUserResponse['data']);
                $EmailVerificationResponse = $mailer->sendEmail();
                if($EmailVerificationResponse['status'] == 'error') {
                    throw new Exception($EmailVerificationResponse['message'], 1);
                }

                $response = $EmailVerificationResponse;

            } else if($userDataResponse['status_detail'] == 'user_found') {
                // arahkan ke screen forgot password
                $response = $userDataResponse;
            }


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
