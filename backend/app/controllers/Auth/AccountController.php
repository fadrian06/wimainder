<?php

namespace App\Controllers\Auth;

class AccountController extends Controller {
  function user(): void {
    $user = auth()->user(['id', 'password']);

    if (!$user) {
      response()->json(auth()->errors(), 401, true);
      return;
    }

    $user = self::sanitizeUser($user);

    response()->json($user, 200, true);
  }

  static function update(): void {
    $data = request()->validate([
      'user' => 'required|username|max:15',
      'name' => 'required'
    ]);

    if (!$data) {
      response()->json(form()->errors(), 400, true);
      return;
    }

    $uniques = ['user'];

    foreach ($uniques as $key => $unique) {
      if (!isset($data[$unique])) {
        unset($uniques[$key]);
      }
    }

    $user = auth()->update($data, $uniques);

    if (!$user) {
      response()->json(auth()->errors(), 500, true);
      return;
    }

    $user = self::sanitizeUser($user['user']);

    response()->json($user, 200, true);
  }
}
