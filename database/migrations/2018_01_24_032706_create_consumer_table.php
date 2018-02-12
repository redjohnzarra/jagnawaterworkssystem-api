<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsumerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumer', function (Blueprint $table) {
            $table->increments('account_no');
            $table->string('lname', '100')->nullable();
            $table->string('fname', '100')->nullable();
            $table->string('mname', '100')->nullable();
            $table->string('address', '100')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('municipality', '50')->nullable();
            $table->string('barangay', '50')->nullable();
            $table->string('citizenship', '20')->nullable();
            $table->string('status', '20')->nullable();
            // $table->integer('age', '10')->nullable();
            $table->string('sex', '10')->nullable();
            $table->string('orno_appfee', '50')->nullable();
            $table->date('application_date')->nullable();
            $table->double('appfee')->nullable();
            $table->binary('signature_of_member')->nullable();
            $table->binary('picture')->nullable();
            $table->integer('consumer_type')->unsigned();
            $table->foreign('consumer_type')
                  ->references('id')->on('consumer_type')
                  ->onDelete('cascade');
            $table->date('connection_date')->nullable();
            $table->integer('meter_number')->nullable();
            $table->double('current_balance')->nullable();
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
        Schema::dropIfExists('consumer');
    }
}
