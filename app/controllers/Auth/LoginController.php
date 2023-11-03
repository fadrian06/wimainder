<?php

namespace App\Controllers\Auth;

use Leaf\Form;

class LoginController extends Controller {
  static function index() {
    $credentials = request()->get(['user', 'password']);

    Form::rule('max', function (?string $field, string $value, string $params) {
      if (strlen($value) > $params) {
        Form::addError($field, "$field cannot be more of $params characters");
        return false;
      }
    });

    $validation = Form::validate($credentials, [
      'user' => ['required', 'max:15'],
      'password' => ['required', 'min:8'],
    ]);

    if (!$validation) {
      response()->exit(Form::errors());
    }

    $user = auth()->login($credentials);

    if (!$user) {
      response()->exit(auth()->errors());
    }

    response()->json($user);
  }

  static function logout() {
    auth()->logout();
    response()->json('Session destroyed succesfully!');
  }
}
