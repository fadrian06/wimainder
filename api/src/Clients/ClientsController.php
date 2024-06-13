<?php

namespace API\Clients;

use Flight;

final readonly class ClientsController {
  function __construct(private ClientRepository $repository) {
  }

  function index(): void {
    Flight::json($this->repository->all());
  }

  function show(string $mac): void {
    $client = $this->repository->search($mac);

    if (!$client) {
      Flight::notFound();
    } else {
      Flight::json($client);
    }
  }

  function update(string $mac): void {
    $data = Flight::request()->data;
    $client = $this->repository->search($mac);

    if (!$client) {
      Flight::notFound();

      return;
    }

    $data['name'] && $client->updateName($data['name']);

    if ($data['isActive'] === true) {
      $client->enable();
    } elseif ($data['isActive'] === false) {
      $client->disable();
    }

    $result = $this->repository->update($client);

    if (!$result->error) {
      Flight::json($client);
    } else {
      Flight::json(['error' => $result->error], 409);
    }
  }

  function destroy(string $mac): void {
    $client = $this->repository->search($mac);

    if ($client) {
      $result = $this->repository->delete($client);

      if ($result->error) {
        Flight::json(['error' => $result->error], 409);

        return;
      }
    }

    Flight::response()->status(204)->send();
  }
}
