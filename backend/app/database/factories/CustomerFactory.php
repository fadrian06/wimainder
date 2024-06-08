<?php

namespace App\Database\Factories;

use App\Models\Customer;

class CustomerFactory extends Factory {
  public $model = Customer::class;

  /**
   * Create the blueprint for your factory
   * @return array
   */
  function definition(): array {
    return [
      'name' => $this->faker->name,
      'mac' => $this->faker->macAddress,
      'blocked' => (bool) rand(0 ,1),
      'user_id' => '3e16c337-7976-42e3-bd6d-c61a18d9a56b'
    ];
  }
}
