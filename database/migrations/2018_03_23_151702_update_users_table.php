<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update('ALTER TABLE `jagnawaterworks`.`user_privileges` ADD COLUMN `name` int AFTER `username`;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::update('ALTER TABLE `jagnawaterworks`.`user_privileges` DROP COLUMN `name`');
    }
}
