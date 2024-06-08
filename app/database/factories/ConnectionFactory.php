<?php

namespace App\Database\Factories;

use App\Models\Connection;
use DateInterval;

class ConnectionFactory extends Factory {
  public $model = Connection::class;

  /**
   * Create the blueprint for your factory
   * @return array
   */
  function definition(): array {
    $endDate = $this->faker->dateTimeThisYear();
    $createdAt = (clone $endDate)->sub(new DateInterval('P1M'));

    return [
      'id' => $this->faker->uuid,
      'end_date' => $endDate->format('Y-m-d'),
      'customer_id' => rand(1, 20),
      'user_id' => '3e16c337-7976-42e3-bd6d-c61a18d9a56b',
      'created_at' => $createdAt,
      'updated_at' => $createdAt
    ];
  }
}
