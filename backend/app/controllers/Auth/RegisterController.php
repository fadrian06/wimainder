<?php

namespace App\Controllers\Auth;

class RegisterController extends Controller {
  static function store(): void {
    $credentials = request()->validate([
      'name' => 'required',
      'user' => 'username|max:15',
      'password' => 'min:8',
      'role_id' => 'role_id'
    ]);

    if (!$credentials) {
      response()->json(request()->errors(), 400, true);
      return;
    }

    $user = auth()->register($credentials, ['user']);

    if (!$user) {
      response()->json(auth()->errors(), 500, true);
      return;
    }

    response()->json($user, 201, true);
  }
}
