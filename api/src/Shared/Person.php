<?php

namespace API\Shared;

use JsonSerializable;
use Stringable;

abstract class Person implements JsonSerializable, Stringable {
  function __construct(
    private string $name,
    private bool $isActive
  ) {
    $this->name = mb_convert_case($name, MB_CASE_TITLE);
  }

  final function updateName(string $name): self {
    $this->name = mb_convert_case($name, MB_CASE_TITLE);

    return $this;
  }

  final function disable(): void {
    $this->isActive = false;
  }

  final function enable(): void {
    $this->isActive = true;
  }

  /** @return array{name: string, isActive: bool} */
  function jsonSerialize(): mixed {
    return [
      'name' => $this->name,
      'isActive' => $this->isActive
    ];
  }

  function __toString(): string {
    return $this->name;
  }
}
