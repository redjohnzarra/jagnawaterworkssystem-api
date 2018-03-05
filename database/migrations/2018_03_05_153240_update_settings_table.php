<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update('ALTER TABLE `jagnawaterworks`.`settings` ADD COLUMN `service_period` int AFTER `updated_at`;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::update('ALTER TABLE `jagnawaterworks`.`settings` DROP COLUMN `service_period`');
    }
}
