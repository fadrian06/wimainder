<?php

use Illuminate\Database\Schema\Blueprint;
use Leaf\Database;

class CreateRoles extends Database {
  /**
   * Run the migrations.
   * @return void
   */
  function up() {
    if (!static::$capsule::schema()->hasTable('roles')):
      static::$capsule::schema()->create('roles', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name')->unique();
        $table->timestamps();
      });
    endif;

    // you can now build your migrations with schemas.
    // see: https://leafphp.dev/docs/mvc/schema.html
    // Schema::build('roles');
  }

  /**
   * Reverse the migrations.
   * @return void
   */
  function down() {
    static::$capsule::schema()->dropIfExists('roles');
  }
}
