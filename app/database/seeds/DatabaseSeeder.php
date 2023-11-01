<?php

namespace App\Database\Seeds;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
  /**
   * Seed your application's database.
   * @return void
   */
  function run(): array {
    return [RolesSeeder::class, UsersSeeder::class, CustomersSeeder::class];
  }
}
