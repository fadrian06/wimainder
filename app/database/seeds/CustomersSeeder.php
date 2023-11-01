<?php

namespace App\Database\Seeds;

use App\Database\Factories\CustomerFactory;
use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder {
  /**
   * Run the database seeds.
   * @return void
   */
  function run() {
    // You can directly create db records

    // $customer = new Customer();
    // $customer->field = 'value';
    // $customer->save();

    // or

    // Customer::create([
    //    'field' => 'value'
    // ]);

    (new CustomerFactory)->create(20)->save();
  }
}
