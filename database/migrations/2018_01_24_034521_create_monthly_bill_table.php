<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_bill', function (Blueprint $table) {
            $table->increments('id');
            $table->string('service_period_end');
            $table->integer('account_no')->unsigned();
            $table->foreign('account_no')
                  ->references('account_no')->on('consumer')
                  ->onDelete('cascade');
            $table->integer('current_reading')->nullable();
            $table->integer('previous_reading')->nullable();
            $table->double('consumption')->nullable();
            $table->double('cubic_meter_amt')->nullable();
            $table->double('charges')->nullable();
            $table->double('net_amount')->nullable();
            $table->dateTime('billing_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('bill_no')->unsigned();
            $table->integer('meter_no')->unsigned();
            $table->string('consumer_type')->nullable();
            $table->double('seniorcitizen_discount')->nullable();
            $table->double('paid')->nullable();
            $table->double('unpaid')->nullable();

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
        Schema::dropIfExists('monthly_bill');
    }
}
