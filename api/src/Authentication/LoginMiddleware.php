<?php

namespace API\Authentication;

use API\Users\User;
use API\Users\UserRepository;
use Flight;

final readonly class LoginMiddleware {
  function __construct(private UserRepository $repository) {
  }

  static function before() {
    self::startSession();

    if (!key_exists('auth', $_SESSION)) {
      header('Content-Type: application/json');
      Flight::halt(401, json_encode(['error' => 'User is not logged']));
    }
  }

  function getLoggedUser(): ?User {
    self::startSession();

    $auth = $_SESSION['auth'] ?? null;

    if (!$auth) {
      return null;
    }

    return $this->repository->search($auth['user']);
  }

  private static function startSession(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }
  }
}
