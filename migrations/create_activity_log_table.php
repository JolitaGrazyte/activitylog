<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->integer('causes_activity_id')->nullable();
            $table->string('causes_activity_type')->nullable();
            $table->integer('logs_activity_id')->nullable();
            $table->string('logs_activity_type')->nullable();
            $table->string('ip_address', 64);
            $table->string('adjustments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('activity_log');
    }
}
