<?php

namespace API\Authentication;

use API\Users\UserRepository;
use Flight;

final readonly class LoginController {
  function __construct(private UserRepository $repository) {
  }

  function __invoke(): void {
    $data = Flight::request()->data;
    $user = $this->repository->search($data['user']);

    if (!$user?->isValidPassword($data['password'])) {
      Flight::json(['error' => 'Invalid user or password'], 401);
    } else {
      if (session_status() !== PHP_SESSION_ACTIVE) {
        $fiveMinutes = 1 * 60 * 5;

        session_start([
          'cookie_lifetime' => $fiveMinutes,
          'cookie_httponly' => true
        ]);
      }

      $_SESSION['auth']['user'] = $user;
      Flight::json(['user' => $user, 'token' => session_id()]);
    }
  }
}
