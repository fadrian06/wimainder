<?php

namespace API\Clients;

use API\Shared\Person;
use API\Shared\Result;
use API\Users\User;
use InvalidArgumentException;

final class Client extends Person {
  public readonly string $mac;

  function __construct(
    string $mac,
    ?string $name,
    bool $isActive,
    public readonly User $registeredBy
  ) {
    parent::__construct($name ?? 'Unknown', $isActive);

    if (!filter_var($mac, FILTER_VALIDATE_MAC)) {
      throw new InvalidArgumentException("Invalid MAC $mac");
    }

    if (!$registeredBy->jsonSerialize()['isActive']) {
      throw new InvalidArgumentException("User $registeredBy is blocked");
    }

    $this->mac = strtolower($mac);
  }

  /** @return Result<?self> */
  static function create(
    string $mac,
    ?string $name,
    User $registeredBy
  ): Result {
    try {
      $isActive = true;
      $client = new self($mac, $name, $isActive, $registeredBy);

      return Result::success($client);
    } catch (InvalidArgumentException $error) {
      return Result::failure($error->getMessage());
    }
  }

  /**
   * @return array{
   *   mac: string,
   *   name: string,
   *   isActive: bool,
   *   registeredBy: array{
   *     user: string,
   *     name: string,
   *     isActive: bool,
   *     role: string
   *   }
   * }
   */
  function jsonSerialize(): mixed {
    return parent::jsonSerialize() + [
      'mac' => $this->mac,
      'registeredBy' => $this->registeredBy->jsonSerialize()
    ];
  }
}
