<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posit_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posit_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('team_user_id');
            $table->foreign('team_user_id')->references('id')->on('team_user')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['posit_id', 'team_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posit_user');
    }
}
