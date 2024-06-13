<?php

namespace API\Connections;

use API\Clients\Client;
use API\Shared\Result;
use API\Users\User;
use DateTimeImmutable;
use InvalidArgumentException;
use JsonSerializable;

final class Connection implements JsonSerializable {
  function __construct(
    public readonly DateTimeImmutable $start,
    public readonly int $days,
    public readonly Client $client,
    public readonly User $registeredBy
  ) {
    if ($days < 1) {
      throw new InvalidArgumentException('Days must be greater than zero');
    }

    if (!$client->jsonSerialize()['isActive']) {
      throw new InvalidArgumentException("Client $client is blocked");
    }

    if (!$registeredBy->jsonSerialize()['isActive']) {
      throw new InvalidArgumentException("User $registeredBy is blocked");
    }
  }

  /** @return Result<?self> */
  static function create(
    int $days,
    Client $client,
    User $registeredBy
  ): Result {
    try {
      $connection = new Connection(
        new DateTimeImmutable,
        $days,
        $client,
        $registeredBy
      );

      return Result::success($connection);
    } catch (InvalidArgumentException $error) {
      return Result::failure($error->getMessage());
    }
  }

  private function getAmountInDollars(): float {
    if ($this->days >= 28 && $this->days <= 31) {
      return 5;
    }

    return $this->days * .5;
  }

  /**
   * @return array{
   *   start: string,
   *   days: int,
   *   amount: float,
   *   client: array{
   *     mac: string,
   *     name: string,
   *     isActive: bool,
   *     registeredBy: array{
   *       user: string,
   *       name: string,
   *       isActive: bool,
   *       role: string
   *     }
   *   },
   *   registeredBy: array{
   *     user: string,
   *     name: string,
   *     isActive: bool,
   *     role: string
   *   }
   * }
   */
  function jsonSerialize(): mixed {
    return [
      'start' => $this->start->format('Y-m-d H:i:s'),
      'days' => $this->days,
      'amountInDollars' => $this->getAmountInDollars(),
      'client' => $this->client->jsonSerialize(),
      'registeredBy' => $this->registeredBy->jsonSerialize()
    ];
  }
}
