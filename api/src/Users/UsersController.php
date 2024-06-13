<?php

namespace API\Users;

use Flight;

final readonly class UsersController {
  function __construct(private UserRepository $repository) {
  }

  function index(): void {
    Flight::json($this->repository->all());
  }

  function show(string $user): void {
    $user = $this->repository->search($user);

    if (!$user) {
      Flight::notFound();
    } else {
      Flight::json($user, 200);
    }
  }

  function store(): void {
    $data = Flight::request()->data;
    $isActive = true;

    $user = new User(
      $data['user'],
      $data['name'],
      $data['password'],
      $isActive,
      Role::from($data->role)
    );

    $result = $this->repository->save($user);

    if (!$result->error) {
      Flight::json($user, 201);
    } else {
      Flight::json(['error' => $result->error], 409);
    }
  }

  function update(string $user): void {
    $data = Flight::request()->data;
    $user = $this->repository->search($user);

    if (!$user) {
      Flight::notFound();

      return;
    }

    if ($data['isActive'] === true) {
      $user->enable();
    } elseif ($data['isActive'] === false) {
      $user->disable();
    }

    $data['name'] && $user->updateName($data['name']);
    $data['role'] && $user->updateRole(Role::from($data['role']));

    if ($data['passwords']) {
      if ($user->isValidPassword($data['passwords']['old'])) {
        $user->updatePassword($data['passwords']['new']);
      } else {
        Flight::json(['error' => 'Invalid old password'], 401);

        return;
      }
    }

    $result = $this->repository->update($user);

    if (!$result->error) {
      Flight::json($user);
    } else {
      Flight::json(['error' => $result->error], 409);
    }
  }

  function destroy(string $user): void {
    $user = $this->repository->search($user);

    if ($user) {
      $result = $this->repository->delete($user);

      if ($result->error) {
        Flight::json(['error' => $result->error], 409);

        return;
      }
    }

    Flight::response()->status(204)->send();
  }
}
