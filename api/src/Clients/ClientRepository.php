<?php

namespace API\Clients;

use API\Shared\Result;
use API\Users\UserRepository;
use SQLite3;

final readonly class ClientRepository {
  function __construct(
    private SQLite3 $sqlite3,
    private UserRepository $userRepository
  ) {
    $query = '
      CREATE TABLE IF NOT EXISTS clients(
        mac VARCHAR(255) PRIMARY KEY CHECK (mac LIKE "%%:%%:%%:%%:%%:%%"),
        name VARCHAR(255),
        active BOOLEAN NOT NULL DEFAULT TRUE,
        registeredBy VARCHAR(255) NOT NULL,

        FOREIGN KEY (registeredBy) REFERENCES users(user)
          ON UPDATE CASCADE
          ON DELETE RESTRICT
      )
    ';

    $this->sqlite3->exec($query);
  }

  /** @return Client[] */
  function all(): array {
    $result = $this->sqlite3->query('SELECT * FROM clients');
    $clients = [];

    while ($row = $result->fetchArray()) {
      $clients[] = new Client(
        $row['mac'],
        $row['name'],
        $row['active'],
        $this->userRepository->search($row['registeredBy'])
      );
    }

    return $clients;
  }

  function search(string $mac): ?Client {
    $stmt = $this->sqlite3->prepare('SELECT * FROM clients WHERE mac = ?');
    $stmt->bindValue(1, $mac);
    $result = $stmt->execute();
    $row = $result->fetchArray();

    return !$row ? null : new Client(
      $row['mac'],
      $row['name'] ?? null,
      $row['active'],
      $this->userRepository->search($row['registeredBy'])
    );
  }

  /** @return Result<?Client> */
  function save(Client $client): Result {
    $query = '
      INSERT INTO clients (mac, name, registeredBy)
      VALUES (:mac, :name, :registeredBy)
    ';

    $stmt = $this->sqlite3->prepare($query);
    $stmt->bindValue(':mac', $client->mac);
    $stmt->bindValue(':name', $client);
    $stmt->bindValue(':registeredBy', $client->registeredBy);
    @$stmt->execute();

    if ($error = $this->sqlite3->lastErrorMsg()) {
      if (str_contains($error, 'clients.mac')) {
        return Result::failure('Invalid or already in use MAC address');
      }

      if (str_contains($error, 'clients.registeredBy')) {
        return Result::failure("User $client->registeredBy not found");
      }
    }

    return Result::success($client);
  }

  /** @return Result<?Client> */
  function update(Client $client): Result {
    $query = '
      UPDATE clients
      SET name = :name, active = :active
      WHERE mac = :mac
    ';

    $stmt = $this->sqlite3->prepare($query);
    $stmt->bindValue(':name', $client);
    $stmt->bindValue(':active', $client->jsonSerialize()['isActive']);
    $stmt->bindValue(':mac', $client->mac);
    $stmt->execute();

    return Result::success($client);
  }

  /** @return Result<?Client> */
  function delete(Client $client): Result {
    $stmt = $this->sqlite3->prepare('DELETE FROM clients WHERE mac = ?');
    $stmt->bindValue(1, $client->mac);
    @$stmt->execute();

    if ($this->sqlite3->lastErrorMsg()) {
      return Result::failure("Client $client has connections associated");
    }

    return Result::success($client);
  }
}
