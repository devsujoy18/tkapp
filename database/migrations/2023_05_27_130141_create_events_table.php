<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('organisation_id');
            $table->foreign('organisation_id')->references('id')->on('organisations');
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->longText('tags')->nullable();
            $table->string('venu_type')->nullable();
            $table->text('full_address')->nullable();
            $table->text('address_one')->nullable();
            $table->text('address_two')->nullable();
            $table->text('city')->nullable();
            $table->text('state')->nullable();
            $table->string('post_code')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('online_link')->nullable();
            $table->date('starts_date')->nullable();
            $table->date('ends_date')->nullable();
            $table->time('starts_time', $precision = 0)->nullable();
            $table->time('ends_time', $precision = 0)->nullable();
            $table->tinyInteger('display_start_time')->default(0);
            $table->tinyInteger('display_end_time')->default(0);
            $table->unsignedBigInteger('timezone_id')->nullable();
            $table->text('video_url')->nullable();
            $table->text('summery')->nullable();
            $table->text('decription')->nullable();
            $table->tinyInteger('is_publish')->default(0);
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
        Schema::dropIfExists('events');
    }
}
