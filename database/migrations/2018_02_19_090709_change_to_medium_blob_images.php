<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeToMediumBlobImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::update('ALTER TABLE `jagnawaterworks`.`consumer` CHANGE COLUMN `signature_of_member` `signature_of_member` mediumblob DEFAULT NULL, CHANGE COLUMN `picture` `picture` mediumblob DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::update('ALTER TABLE `jagnawaterworks`.`consumer` CHANGE COLUMN `signature_of_member` `signature_of_member` blob DEFAULT NULL, CHANGE COLUMN `picture` `picture` blob DEFAULT NULL');
    }
}
