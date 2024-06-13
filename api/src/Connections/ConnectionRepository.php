<?php

namespace API\Connections;

use API\Clients\ClientRepository;
use API\Shared\Result;
use API\Users\UserRepository;
use DateTimeImmutable;
use SQLite3;

final readonly class ConnectionRepository {
  function __construct(
    private SQLite3 $sqlite3,
    private ClientRepository $clientRepository,
    private UserRepository $userRepository
  ) {
    $query = '
      CREATE TABLE IF NOT EXISTS connections(
        start DATETIME PRIMARY KEY,
        days INTEGER NOT NULL CHECK (days > 0),
        mac VARCHAR(255) NOT NULL CHECK (mac LIKE "%%:%%:%%:%%:%%:%%"),
        registeredBy VARCHAR(255) NOT NULL,

        FOREIGN KEY (mac) REFERENCES clients(mac)
          ON UPDATE CASCADE
          ON DELETE RESTRICT,

        FOREIGN KEY (registeredBy) REFERENCES users(user)
          ON UPDATE CASCADE
          ON DELETE RESTRICT
      )
    ';

    $this->sqlite3->exec($query);
  }

  /** @return Connection[] */
  function all(): array {
    $result = $this->sqlite3->query('SELECT * FROM connections');
    $connections = [];

    while ($row = $result->fetchArray()) {
      $connections[] = new Connection(
        new DateTimeImmutable($row['start']),
        $row['days'],
        $this->clientRepository->search($row['mac']),
        $this->userRepository->search($row['registeredBy'])
      );
    }

    return $connections;
  }

  function search(DateTimeImmutable $startDate): ?Connection {
    $entireRow = true;
    $start = $startDate->format('Y-m-d H:i:s');
    $query = "SELECT * FROM connections WHERE start = '$start'";

    $row = $this->sqlite3->querySingle($query, $entireRow);

    return !$row ? null : new Connection(
      new DateTimeImmutable($row['start']),
      $row['days'],
      $this->clientRepository->search($row['mac']),
      $this->userRepository->search($row['registeredBy'])
    );
  }

  /** @return Result<?Connection> */
  function save(Connection $connection): Result {
    $query = '
      INSERT INTO connections(start, days, mac, registeredBy)
      VALUES (:start, :days, :mac, :registeredBy)
    ';

    $stmt = $this->sqlite3->prepare($query);
    $stmt->bindValue(':start', $connection->start->format('Y-m-d H:i:s'));
    $stmt->bindValue(':days', $connection->days);
    $stmt->bindValue(':mac', $connection->client->mac);
    $stmt->bindValue(':registeredBy', $connection->registeredBy);
    @$stmt->execute();

    if ($error = $this->sqlite3->lastErrorMsg()) {
      if (str_contains($error, 'connections.start')) {
        return Result::failure('Start date is already in use');
      }

      if (str_contains($error, 'connections.days')) {
        return Result::failure('Days must be greater then zero');
      }

      if (str_contains($error, 'connections.mac')) {
        return Result::failure("Invalid MAC $connection->client->mac");
      }
    }

    return Result::success($connection);
  }

  /** @return Result<?Connection> */
  function delete(Connection $connection): Result {
    $start = $connection->start->format('Y-m-d H:i:s');
    $query = "DELETE FROM connections WHERE start = '$start'";
    $this->sqlite3->exec($query);

    return Result::success($connection);
  }
}
