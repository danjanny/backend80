<?php

$routes->group("auth", ["namespace" => "\Modules\Auth\Controllers"], function ($routes) {

	$routes->get("/", "AuthController::detail");

	$routes->post("register", "RegisterController::register");

	$routes->post("login", "LoginController::login");
	$routes->post("change-password", "ForgotPasswordController::changePassword");

	$routes->get("index", "AuthController::detail");
});
