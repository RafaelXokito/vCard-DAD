<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddAuthUserView extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    DB::statement("
      CREATE VIEW view_auth_users AS
        SELECT phone_number as id, 'V' as user_type, phone_number as username, password, name, email, blocked, confirmation_code, photo_url, deleted_at from vcards
        union
        SELECT id, 'A', email, password, name, email, 0, null, null, null from users
    ");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    DB::statement("DROP VIEW view_auth_users");
  }
}
