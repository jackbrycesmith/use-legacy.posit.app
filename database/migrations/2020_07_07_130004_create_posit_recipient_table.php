<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositRecipientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posit_recipient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posit_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_contact_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['posit_id', 'team_contact_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posit_recipient');
    }
}
