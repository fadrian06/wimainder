<?php

namespace API\Shared;

/** @template T */
final readonly class Result {
  /** @param T $value */
  private function __construct(
    public mixed $value,
    public string $error
  ) {}

  /**
   * @template T
   * @param T $value
   * @return self<T>
   */
  static function success(mixed $value): self {
    return new self($value, '');
  }

  /** @return self<null> */
  static function failure(string $error): self {
    return new self(null, $error);
  }
}
