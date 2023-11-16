<?php

use App\Controllers\Auth\AccountController;
use App\Controllers\Auth\LoginController;
use App\Controllers\Auth\RegisterController;

app()->group('/auth', function () {
  app()->post('/login',    [LoginController::class, 'index']);
  app()->post('/register', [RegisterController::class, 'store']);
  // app()->get('/logout',    [LoginController::class, 'logout']);
});

app()->group('/user', function () {
  app()->get('/',        [new AccountController, 'user']);
  app()->post('/update', [AccountController::class, 'update']);
});
