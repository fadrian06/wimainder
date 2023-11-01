<?php

namespace App\Controllers\Auth;

class AccountController extends Controller {
  function user() {
    $user = auth()->user(['password']);

    if (!$user) {
      response()->exit(auth()->errors());
    }

    response()->json($user);
  }

  function update() {
    $data = request()->try(['user', 'name'], true, true);
    $uniques = ['user', 'email'];

    foreach ($uniques as $key => $unique) {
      if (!isset($data[$unique])) {
        unset($uniques[$key]);
      }
    }

    $user = auth()->update($data, $uniques);

    if (!$user) {
      response()->exit(auth()->errors());
    }

    response()->json($user);
  }
}
