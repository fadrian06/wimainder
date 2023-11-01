<?php

namespace App\Database\Factories;

use App\Models\User;
use Symfony\Component\Uid\Uuid;
use Leaf\Helpers\Password;

class UserFactory extends Factory {
  public $model = User::class;

  /**
   * Create the blueprint for your factory
   * @return array
   */
  function definition(): array {
    $user = $this->faker->userName;

    return [
      'id' => Uuid::v4(),
      'name' => $this->faker->name,
      'user' => $user,
      'password' => Password::hash($user),
      'role_id' => rand(2, 3)
    ];
  }
}
