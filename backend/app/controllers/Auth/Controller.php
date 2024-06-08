<?php

namespace App\Controllers\Auth;

use App\Models\Role;

/**
 * This is a base controller for the auth namespace
 */
class Controller extends \App\Controllers\Controller {
  static function sanitizeUser(array $user): array {
    $user['role'] = Role::find($user['role_id'])['name'];
    $user['active'] = (bool) $user['active'];
    unset($user['role_id']);

    return $user;
  }
}
