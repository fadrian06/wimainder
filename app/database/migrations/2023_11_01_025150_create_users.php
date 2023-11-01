<?php

use App\Models\Role;
use Leaf\Database;
use Illuminate\Database\Schema\Blueprint;

class CreateUsers extends Database {
  /**
   * Run the migrations.
   * @return void
   */
  function up() {
    if (!static::$capsule::schema()->hasTable('users')) :
      static::$capsule::schema()->create('users', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->string('name');
        $table->string('user')->unique();
        $table->string('password');
        $table->foreignIdFor(Role::class);
        $table->timestamps();
      });
    endif;

    // you can now build your migrations with schemas.
    // see: https://leafphp.dev/docs/mvc/schema.html
    // Schema::build('users');
  }

  /**
   * Reverse the migrations.
   * @return void
   */
  function down() {
    static::$capsule::schema()->dropIfExists('users');
  }
}
