<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_access_polls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id');
            $table->foreignId('poll_id');
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('user_groups');
            $table->foreign('poll_id')->references('id')->on('polls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_access_polls');
    }
};
