<?php

namespace Modules\Auth\Models;

use CodeIgniter\Model;
use Exception;
use Modules\Auth\Library\Util;

class AuthModel extends Model
{

    protected $db;

    private $user_app_tbl = 'user_app';

    public function __construct() {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    /**
     * Register check username / email
     * @param  [type] $params | username or email
     * @return status, message, data
     */
    public function checkUsername($params) {
        $response = [];
        try {
            $builder = $this->db->table($this->user_app_tbl);

            // if username or email has available, arahkan user ke forgot password
            $builder->where('username', $params['username'] );
            $builder->orWhere('email', $params['email'] );
            $query = $builder->get();

            if(empty($query->getResultArray() )) {
                $response = [
                    'status' => 'ok',
                    'status_detail' => 'no_user_data'
                ];  
            } else {
                $response = [
                    'status' => 'ok',
                    'status_detail' => 'user_found'
                    // 'data' => $query->getResultArray()
                ];
            }

        } catch(Exception $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

        return $response;

    }

    public function addUser($params) {
        $response = [];
        try {
            $builder = $this->db->table($this->user_app_tbl);

            // generate verification code
            $verificationCode = $this->getVerificationCode(4);

            $addUserData = [
                'username' => str_replace([' ', '-'], '', $params['username']),
                'email' => $params['email'],
                'password' => password_hash($params['password'], PASSWORD_DEFAULT), // hashed password
                'full_nm' => $params['full_nm'],
                'image' => $params['username'] . '.jpg',
                'verif_code' => $verificationCode,
                'tgl' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
                'modified_at' => date('Y-m-d H:i:s')
            ];

            $userId = null;
            if($builder->insert($addUserData) ) {
                $userId = $this->db->insertID();
            } else {
                throw new Exception("Error insert new row of user", 1);
            }

            $addUserData['id'] = $userId;
            $response = [
                'status' => 'ok',
                'message' => 'User berhasil ditambahkan',
                'data' => $addUserData
            ];

        } catch(Exception $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];            
        }

        return $response;

    }

    public function getUser($params) {
        $response = [];
        try {

            $builder = $this->db->table($this->user_app_tbl);

            $builder->where('username', $params['username']);
            $builder->orWhere('email', $params['username']); // username or email
            // $builder->where(['password' => $params['password'], 'is_verified' => 1 ]); // user should be verified

            $userRow = [];
            if($qUser = $builder->get()) {
                $userRow = $qUser->getRowArray();
            } 

            // cek username / email sudah terdaftar ?
            if(empty($userRow)) {
                throw new Exception("No user found. Please Sign Up", 1);
            }

            // cek hashed password
            if(!password_verify($params['password'], $userRow['password'] )) {
                throw new Exception("Username or password is invalid", 1);
            }

            // cek user belum verifikasi account
            if($userRow['is_verified'] == 0) {
                throw new Exception("Unverified user. Cek email anda : {$userRow['email']}", 1);
            }

            $response = [
                'status' => 'ok',
                'data' => $userRow
            ];

        } catch(Exception $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

        return $response;
    }

    private function getVerificationCode($length) {
        $builder = $this->db->table($this->user_app_tbl);

        $randomNumber = Util::getRandomNumber($length);
        $builder->where('verif_code', $randomNumber);

        // if verif code sudah ada, generate kode baru
        if($builder->countAllResults() > 0) {
            $this->getVerificationCode(); // recursion
        }

        return $randomNumber;
    }

}
