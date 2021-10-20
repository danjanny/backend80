<?php

namespace Modules\Student\Controllers;

use App\Controllers\BaseController;
use Modules\Student\Seeders\UserSeeder;
use Modules\Student\Models\UserModel;

class StudentController extends BaseController
{

	public function __construct() {
	}

	public function index()
	{
		// echo "This is simple from Student Module";

        $data = [ "name" => "Ridha", "email" => "ridhadanjanny.mail@gmail.com" ];

		return view("\Modules\Student\Views\student_index", $data);
	}
  
    public function otherMethod()
	{
		echo "This is other method from Student Module";
	}

    public function seed()
	{
		echo UserModel::getUser();
	}	
}