<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posits', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->string('type')->index();
            $table->string('state')->index();
            $table->jsonb('config');
            $table->string('name')->nullable(); // TODO change to text type?

            $valueTotal = config('posit-settings.posit.value_max_digits') + config('posit-settings.posit.value_digits_round');
            $valuePlaces = config('posit-settings.posit.value_digits_round');
            $table->unsignedDecimal('value_amount', $valueTotal, $valuePlaces)->nullable();

            $table->string('value_currency_code', 3)->nullable()->index();
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('posits');
    }
}
