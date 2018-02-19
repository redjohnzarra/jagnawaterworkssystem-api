<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReadingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reading', function (Blueprint $table) {
            $table->increments('id');
            $table->string('service_period_end');
            $table->integer('account_no')->unsigned();
            $table->foreign('account_no')
                  ->references('account_no')->on('consumer')
                  ->onDelete('cascade');
            $table->dateTime('reading_date');
            $table->integer('read_by')->unsigned();
            $table->foreign('read_by')
                  ->references('id')->on('user_privileges')
                  ->onDelete('cascade');
            $table->integer('current_reading')->nullable();
            $table->integer('previous_reading')->nullable();
            $table->integer('meter_number')->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reading');
    }
}
