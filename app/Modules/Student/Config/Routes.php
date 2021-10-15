<?php

$routes->group("student", ["namespace" => "\Modules\Student\Controllers"], function ($routes) {

	// welcome page - URL: /student
	$routes->get("/", "StudentController::index");
  
    // other page - URL: /student/other-method
	$routes->get("other-method", "StudentController::otherMethod");
});