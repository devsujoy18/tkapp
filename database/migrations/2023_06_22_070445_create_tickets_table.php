<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events');
            $table->string('ticket_type')->nullable();
            $table->string('ticket_name');
            $table->integer('quantity')->default(0);
            $table->float('given_cost', 8,2)->default(0.00);
            $table->float('service_fee_per', 8,2)->default(0.00);
            //$table->float('service_fee_per_cost', 8,2)->default(0.00);
            $table->float('service_fee_amount_val', 8,2)->default(0.00);
            //$table->float('service_fee_amount_val_cost', 8,2)->default(0.00);
            $table->float('processing_fee_per', 8,2)->default(0.00);
            //$table->float('processing_fee_per_cost', 8,2)->default(0.00);
            $table->float('cost_to_buyer', 8,2)->default(0.00);
            $table->integer('absorb_fee')->default(0);
            $table->integer('ticket_per_order_min')->default(0);
            $table->integer('ticket_per_order_max')->default(0);
            $table->date('sales_starts_date')->nullable();
            $table->date('sales_ends_date')->nullable();
            $table->time('sales_starts_time', $precision = 0)->nullable();
            $table->time('sales_ends_time', $precision = 0)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('tickets');
    }
}
