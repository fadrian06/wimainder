<?php

namespace App\Database\Seeds;

use App\Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Leaf\Helpers\Password;
use Symfony\Component\Uid\Uuid;
use App\Models\User;

class UsersSeeder extends Seeder {
  /**
   * Run the database seeds.
   * @return void
   */
  function run() {
    // You can directly create db records

    // $user = new User();
    // $user->field = 'value';
    // $user->save();

    // or

    User::create([
      'id' => Uuid::v4(),
      'name' => 'Administrador',
      'user' => 'admin',
      'password' => Password::hash('admin123'),
      'role_id' => 1
    ]);

    (new UserFactory)->create(10)->save();
  }
}
