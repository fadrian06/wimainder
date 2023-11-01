<?php

namespace App\Controllers\Auth;

use Leaf\Form;

class RegisterController extends Controller {
  static function store() {
    $credentials = request()->get(['name', 'user', 'password']);

    $validation = Form::validate($credentials, [
      'name' => 'required',
      'user' => ['username', 'max:15'],
      'clave' => 'min:8'
    ]);

    if (!$validation) response()->exit(Form::errors());

    $user = auth()->register($credentials, ['user']);

    if (!$user) response()->exit(auth()->errors());

    response()->json($user);
  }
}
