<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicefeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicefees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organisation_id')->default(0);
            $table->float('percentage_val', 8,2)->default(0.00);
            $table->float('amount_val', 8,2)->default(0.00);
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
        Schema::dropIfExists('servicefees');
    }
}
