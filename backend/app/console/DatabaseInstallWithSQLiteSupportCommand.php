<?php

namespace App\Console;

use Aloe\Command;
use SQLite3;
use Throwable;

class DatabaseInstallWithSQLiteSupportCommand extends Command {
  protected static $defaultName = 'db:install-v2';
  public $description = 'Create new database from .env variables (support for SQLite)';
  public $help = 'Create new database from .env variables (support for SQLite)';

  protected function handle() {
    $connection = DatabaseConfig()['default'];

    return match (strtolower($connection)) {
      'mysql' => $this->createMysqlDb(),
      'sqlite' => $this->createSqliteDb(),
      default => $this->error("DB_CONNECTION=$connection not supported.")
    };
  }

  private function createMysqlDb(): int {
    $config = DatabaseConfig()['connections']['mysql'];
    $host = $config['host'];
    $user = $config['username'];
    $password = $config['password'];
    $port = $config['port'];
    $database = $config['database'];

    if (mysqli_query(
      mysqli_connect($host, $user, $password, '', $port),
      "CREATE DATABASE `$database`"
    )) {
      $this->info("$database created successfully.");

      return 0;
    }

    $this->error("$database could not be created.");

    return 1;
  }

  private function createSqliteDb(): int {
    $config = DatabaseConfig()['connections']['sqlite'];
    $database = $config['database'];

    if (str_starts_with($database, '//')) {
      $database = substr($database, 2);
    }

    try {
      new SQLite3($database);
      $this->info("Database $database created successfully.");

      return 1;
    } catch (Throwable) {
      $this->error("Database $database could not be created.");

      return 0;
    }
  }
}
