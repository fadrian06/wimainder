<?php

namespace API\Connections;

use API\Clients\Client;
use API\Clients\ClientRepository;
use API\Users\User;
use DateTimeImmutable;
use Flight;

final readonly class ConnectionsController {
  function __construct(
    private ConnectionRepository $connectionRepository,
    private ClientRepository $clientRepository,
    private User $loggedUser
  ) {
  }

  function index(): void {
    Flight::json($this->connectionRepository->all());
  }

  function show(string $startDate): void {
    $startDate = new DateTimeImmutable($startDate);
    $connection = $this->connectionRepository->search($startDate);

    if (!$connection) {
      Flight::notFound();
    } else {
      Flight::json($connection);
    }
  }

  function store(): void {
    $data = Flight::request()->data;

    if (!key_exists('mac', $data['client'] ?? [])) {
      Flight::json(['error' => 'Client MAC is required'], 400);

      return;
    }

    $client = $this->clientRepository->search($data['client']['mac']);

    if (!$client) {
      $clientValidationResult = Client::create(
        $data['client']['mac'],
        $data['client']['name'] ?? null,
        $this->loggedUser
      );

      if ($clientValidationResult->error) {
        Flight::json(['error' => $clientValidationResult->error], 400);

        return;
      }

      $client = $clientValidationResult->value;
      $clientSavedResult = $this->clientRepository->save($client);

      if ($clientSavedResult->error) {
        Flight::json(['error' => $clientSavedResult->error], 409);

        return;
      }
    }

    $connectionValidationResult = Connection::create(
      $data['days'],
      $client,
      $this->loggedUser
    );

    if ($connectionValidationResult->error) {
      Flight::json(['error' => $connectionValidationResult->error], 400);

      return;
    }

    $connection = $connectionValidationResult->value;
    $connectionSavedResult = $this->connectionRepository->save($connection);

    if ($connectionSavedResult->error) {
      Flight::json(['error' => $connectionSavedResult->error], 409);
    } else {
      Flight::json($connection);
    }
  }

  function destroy(string $startDate): void {
    $startDate = new DateTimeImmutable($startDate);
    $connection = $this->connectionRepository->search($startDate);

    if ($connection) {
      $this->connectionRepository->delete($connection);
    }

    Flight::response()->status(204)->send();
  }
}
