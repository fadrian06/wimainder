<?php

namespace API\Users;

use API\Shared\Result;
use SQLite3;

final readonly class UserRepository {
  function __construct(private SQLite3 $sqlite3) {
    $roles = Role::cases();
    $roles = array_map(fn (Role $role) => "'$role->value'", $roles);
    $roles = join(',', $roles);

    $query = "
      CREATE TABLE IF NOT EXISTS users(
        user VARCHAR(255) PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        active BOOLEAN NOT NULL DEFAULT TRUE,
        role VARCHAR(255) NOT NULL CHECK (role IN ($roles))
      )
    ";

    $this->sqlite3->exec($query);
  }

  /** @return User[] */
  function all(): array {
    $result = $this->sqlite3->query('SELECT * FROM users');
    $users = [];

    while ($row = $result->fetchArray()) {
      $users[] = new User(
        $row['user'],
        $row['name'],
        $row['password'],
        $row['active'],
        Role::from($row['role'])
      );
    }

    return $users;
  }

  function search(string $user): ?User {
    $stmt = $this->sqlite3->prepare('SELECT * FROM users WHERE user = ?');
    $stmt->bindValue(1, $user);
    $result = $stmt->execute();
    $row = $result->fetchArray();

    return !$row ? null : new User(
      $row['user'],
      $row['name'],
      $row['password'],
      $row['active'],
      Role::from($row['role'])
    );
  }

  /** @return Result<?User> */
  function save(User $user): Result {
    $query = '
      INSERT INTO users(user, name, password, role)
      VALUES (:user, :name, :password, :role)
    ';

    $userArray = $user->jsonSerialize();
    $stmt = $this->sqlite3->prepare($query);
    $stmt->bindValue(':user', $user);
    $stmt->bindValue(':name', $userArray['name']);
    $stmt->bindValue(':password', $user->getPassword());
    $stmt->bindValue(':role', $userArray['role']);
    @$stmt->execute();

    if ($error = $this->sqlite3->lastErrorMsg()) {
      if (str_contains($error, 'users.user')) {
        return Result::failure("User $user already exists");
      }
    }

    return Result::success($user);
  }

  /** @return Result<?User> */
  function update(User $user): Result {
    $query = '
      UPDATE users
      SET name = :name, password = :password, active = :active, role = :role
      WHERE user = :user
    ';

    $userArray = $user->jsonSerialize();
    $stmt = $this->sqlite3->prepare($query);
    $stmt->bindValue(':name', $userArray['name']);
    $stmt->bindValue(':password', $user->getPassword());
    $stmt->bindValue(':active', $userArray['isActive']);
    $stmt->bindValue(':role', $userArray['role']);
    $stmt->bindValue(':user', $user);
    $stmt->execute();

    return Result::success($user);
  }

  /** @return Result<?User> */
  function delete(User $user): Result {
    $stmt = $this->sqlite3->prepare('DELETE FROM users WHERE user = ?');
    $stmt->bindValue(1, $user);
    @$stmt->execute();

    if ($this->sqlite3->lastErrorMsg()) {
      return Result::failure("User $user has clients or connections associated");
    }

    return Result::success($user);
  }
}
