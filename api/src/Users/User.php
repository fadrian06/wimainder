<?php

namespace API\Users;

use API\Shared\Person;

final class User extends Person {
  function __construct(
    private readonly string $user,
    string $name,
    private string $password,
    bool $isActive,
    private Role $role
  ) {
    parent::__construct($name, $isActive);

    if (!str_starts_with($password, '$2y')) {
      $password = password_hash($password, PASSWORD_DEFAULT);
    }

    $this->password = $password;
  }

  function getPassword(): string {
    return $this->password;
  }

  function isValidPassword(string $password): bool {
    return password_verify($password, $this->password);
  }

  function updatePassword(string $password): self {
    $this->password = password_hash($password, PASSWORD_DEFAULT);

    return $this;
  }

  function updateRole(Role $role): self {
    $this->role = $role;

    return $this;
  }

  /** @return array{user: string, name: string, isActive: bool, role: string} */
  function jsonSerialize(): mixed {
    return parent::jsonSerialize() + [
      'user' => $this->user,
      'role' => $this->role->value
    ];
  }

  function __toString(): string {
    return $this->user;
  }
}
