<?php

use Leaf\Schema;
use Leaf\Database;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Customer;
use App\Models\User;

class CreateConnections extends Database {
  /**
   * Run the migrations.
   * @return void
   */
  function up() {
    if (!static::$capsule::schema()->hasTable('connections')):
      static::$capsule::schema()->create(
        'connections',
        function (Blueprint $table) {
          $table->increments('id');
          $table->date('end_date');
          $table->foreignIdFor(Customer::class);
          $table->foreignIdFor(User::class);
          $table->timestamps();
        }
      );
    endif;

    // you can now build your migrations with schemas.
    // see: https://leafphp.dev/docs/mvc/schema.html
    // Schema::build('connections');
  }

  /**
   * Reverse the migrations.
   * @return void
   */
  function down() {
    static::$capsule::schema()->dropIfExists('connections');
  }
}
