<?php

namespace App\Database\Seeds;

use App\Database\Factories\ConnectionFactory;
use Illuminate\Database\Seeder;

class ConnectionsSeeders extends Seeder {
  /**
   * Run the database seeds.
   * @return void
   */
  function run() {
    // You can directly create db records

    // $connectionsseeder = new ConnectionsSeeder();
    // $connectionsseeder->field = 'value';
    // $connectionsseeder->save();

    // or

    // ConnectionsSeeder::create([
    //    'field' => 'value'
    // ]);
    (new ConnectionFactory)->create(20)->save();
  }
}
