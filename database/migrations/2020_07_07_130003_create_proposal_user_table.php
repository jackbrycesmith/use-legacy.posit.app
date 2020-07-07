<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('organisation_member_id')->nullable();
            $table->foreign('organisation_member_id')->references('id')->on('organisation_member')->onDelete('cascade'); // TODO not sure of this cascade behaviour
            $table->foreignId('organisation_contact_id')->nullable()->constrained()->onDelete('cascade');
            // TODO roles/abilties/permissions thing
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
        Schema::dropIfExists('proposal_user');
    }
}
