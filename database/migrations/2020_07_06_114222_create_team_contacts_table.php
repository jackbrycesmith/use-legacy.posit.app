<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_contacts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // TODO they might become a user?
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->json('meta'); // TODO separate fields for name, emails, something to store their public encryption keys?
            $table->string('access_code', 75)->collate('utf8_bin'); // e.g. to access proposals they're allowed to access
            $table->timestamps();

            $table->unique(['team_id', 'access_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_contacts');
    }
}
