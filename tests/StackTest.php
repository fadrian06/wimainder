<?php

use PHPUnit\Framework\TestCase;

final class StackTest extends TestCase {
  public function testPushAndPop(): void {
    $stack = [];
    self::assertSame(0, count($stack));

    array_push($stack, 'foo');
    self::assertSame('foo', $stack[count($stack) - 1]);
    self::assertSame(1, count($stack));

    self::assertSame('foo', array_pop($stack));
    self::assertSame(0, count($stack));
  }
}
