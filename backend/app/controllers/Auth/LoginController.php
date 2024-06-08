<?php

namespace App\Controllers\Auth;

class LoginController extends Controller {
  static function index(): void {
    $credentials = request()->validate([
      'user' => 'required|max:15',
      'password' => 'required|min:8',
    ]);

    if (!$credentials) {
      response()->json(request()->errors(), 400, true);
      return;
    }

    $user = auth()->login($credentials);

    if (!$user) {
      response()->json(auth()->errors(), 401, true);
      return;
    }

    $user['user'] = self::sanitizeUser($user['user']);

    response()->json($user, 200, true);
  }

  static function logout(): void {
    auth()->logout();
    response()->json('Session destroyed succesfully!', 200, true);
  }
}
