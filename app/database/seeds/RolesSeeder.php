<?php

namespace App\Database\Seeds;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder {
  /**
   * Run the database seeds.
   * @return void
   */
  function run() {
    Role::create(['name' => 'Administrator']);
    Role::create(['name' => 'Helper']);
    Role::create(['name' => 'Observer']);
  }
}
