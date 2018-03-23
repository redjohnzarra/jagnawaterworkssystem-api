<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTableNameLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update('ALTER TABLE `jagnawaterworks`.`user_privileges` CHANGE COLUMN `name` `name` varchar(255) DEFAULT NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::update('ALTER TABLE `jagnawaterworks`.`user_privileges` CHANGE COLUMN `name` `name` varchar(11) DEFAULT NULL;');
    }
}
