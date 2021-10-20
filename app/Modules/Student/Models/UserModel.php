<?php

namespace Modules\Student\Models;

use CodeIgniter\Model;

class UserModel {

	private $db;

	private static $user_app_tbl = 'user_app';	

	public function __construct(){
		// parent::__construct();
		$this->db = \Config\Database::connect();
		echo 'constructor init';
	}

	public static function getUser() {
		$db = \Config\Database::connect();
		$builder = $db->table(self::$user_app_tbl);
		$query = $builder->get();
		var_dump($query->getResult() );
	}

}