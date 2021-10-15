<?php

namespace App\Controllers;

use chriskacerguis\RestServer\RestController;
use App\Libraries\Export\Excel;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function hello() {
        $excel = new Excel();
        echo $excel->export();
    }
}
