<?php

namespace App\Database\Seeds;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder {
  /**
   * Run the database seeds.
   * @return void
   */
  public function run() {
    // You can directly create db records

    // $role = new Role();
    // $role->field = 'value';
    // $role->save();

    // or

    Role::create(['name' => 'Administrator']);
    Role::create(['name' => 'Helper']);
    Role::create(['name' => 'Observer']);
  }
}
