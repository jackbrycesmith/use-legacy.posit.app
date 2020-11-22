<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInAppCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('in_app_credits', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->enum('type', ['increase', 'decrease']);
            $table->unsignedMediumInteger('amount');
            $table->json('meta')->nullable();

            $table->numericMorphs('balance_model');
            $table->nullableNumericMorphs('usage_model');
            $table->nullableNumericMorphs('initiator_model');

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
        Schema::dropIfExists('in_app_credits');
    }
}
