<?php

use App\Models\User;
use Leaf\Schema;
use Leaf\Database;
use Illuminate\Database\Schema\Blueprint;

class CreateCustomers extends Database {
  /**
   * Run the migrations.
   * @return void
   */
  function up() {
    if (!static::$capsule::schema()->hasTable('customers')) :
      static::$capsule::schema()->create(
        'customers',
        function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->macAddress('mac')->unique();
          $table->boolean('blocked')->default(false);
          $table->foreignIdFor(User::class);
          $table->timestamps();
        }
      );
    endif;

    // you can now build your migrations with schemas.
    // see: https://leafphp.dev/docs/mvc/schema.html
    // Schema::build('customers');
  }

  /**
   * Reverse the migrations.
   * @return void
   */
  function down() {
    static::$capsule::schema()->dropIfExists('customers');
  }
}
