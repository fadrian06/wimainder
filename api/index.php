<?php

use API\Authentication\LoginController;
use API\Authentication\LoginMiddleware;
use API\Clients\ClientsController;
use API\Connections\ConnectionsController;
use API\Users\User;
use API\Users\UserRepository;
use API\Users\UsersController;
use Illuminate\Container\Container;

require 'vendor/autoload.php';

date_default_timezone_set('America/Caracas');

define('MAC_REGEXP', (function (): string {
  $caracters = '[a-fA-F0-9]{2}';
  $separator = ':';

  return str_repeat($caracters . $separator, 5) . $caracters;
})());

Flight::map('error', function (Throwable $error): void {
  ini_set('error_log', __DIR__ . '/errors.log');
  error_log($error);
  Flight::halt(500, $error->getMessage());
});

$container = new Container;

$container->singleton(UserRepository::class);

$container->bind(SQLite3::class, function (): SQLite3 {
  $sqlite3 = new SQLite3(__DIR__ . '/wimainder.db');
  $sqlite3->exec('PRAGMA foreign_keys = ON');

  return $sqlite3;
});

$container->singleton(User::class, function (Container $container): User {
  $middleware = $container->get(LoginMiddleware::class);
  assert($middleware instanceof LoginMiddleware);

  return $middleware->getLoggedUser() ?? $middleware->before();
});

Flight::registerContainerHandler(fn (string $class) => $container->get($class));

Flight::route('POST /login', [LoginController::class, '__invoke']);

Flight::group('/users', function (): void {
  Flight::route('GET /', [UsersController::class, 'index']);
  Flight::route('POST /', [UsersController::class, 'store']);

  Flight::route('GET /@user', [UsersController::class, 'show']);
  Flight::route('PATCH /@user', [UsersController::class, 'update']);
  Flight::route('DELETE /@user', [UsersController::class, 'destroy']);
}, [LoginMiddleware::class]);

Flight::group('/clients', function (): void {
  Flight::route('GET /', [ClientsController::class, 'index']);

  Flight::group('/@mac:' . MAC_REGEXP, function (): void {
    Flight::route('GET /', [ClientsController::class, 'show']);
    Flight::route('PATCH /', [ClientsController::class, 'update']);
    Flight::route('DELETE /', [ClientsController::class, 'destroy']);
  });
}, [LoginMiddleware::class]);

Flight::group('/connections', function (): void {
  Flight::route('GET /', [ConnectionsController::class, 'index']);
  Flight::route('POST /', [ConnectionsController::class, 'store']);

  Flight::group('/@startDate', function (): void {
    Flight::route('GET /', [ConnectionsController::class, 'show']);
    Flight::route('DELETE /', [ConnectionsController::class, 'destroy']);
  });
}, [LoginMiddleware::class]);

Flight::start();
