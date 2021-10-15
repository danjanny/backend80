<?php

namespace Modules\Student\Controllers;

use App\Controllers\BaseController;

class StudentController extends BaseController
{
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
}