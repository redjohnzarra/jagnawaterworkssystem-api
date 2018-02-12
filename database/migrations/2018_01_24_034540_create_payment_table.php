<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_no');
            $table->integer('account_no')->unsigned();
            $table->foreign('account_no')
                  ->references('account_no')->on('consumer')
                  ->onDelete('cascade');
            $table->string('bill_no')->nullable();
            $table->double('total_amount')->nullable();
            $table->datetime('payment_date')->nullable();
            $table->double('penalty')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('or_no')->nullable();
            $table->string('teller')->nullable();
            $table->datetime('or_date')->nullable();
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
        Schema::dropIfExists('payment');
    }
}
