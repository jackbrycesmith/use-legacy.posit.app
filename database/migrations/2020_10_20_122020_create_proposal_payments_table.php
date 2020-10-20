<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('proposal_id')->constrained()->onDelete('cascade'); // Not sure I'd want to do this?
            $table->string('provider')->nullable()->index(); // e.g. 'stripe'
            $table->string('type')->nullable()->index(); // e.g. 'deposit' or null

            $valueTotal = config('posit-settings.proposal.value_max_digits') + config('posit-settings.proposal.value_digits_round');
            $valuePlaces = config('posit-settings.proposal.value_digits_round');
            $table->unsignedDecimal('amount', $valueTotal, $valuePlaces)->nullable();

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
        Schema::dropIfExists('proposal_payments');
    }
}
