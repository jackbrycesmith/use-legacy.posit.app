<?php

use CloudCreativity\LaravelStripe\LaravelStripe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeCheckoutSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_checkout_sessions', function (Blueprint $table) {
            $table->string('id', 255)->primary()->collate(LaravelStripe::ID_DATABASE_COLLATION);
            $table->string('stripe_account_id', 255)->nullable()->collate(LaravelStripe::ID_DATABASE_COLLATION);
            $table->string('stripe_customer_id', 255)->nullable()->collate(LaravelStripe::ID_DATABASE_COLLATION);
            $table->string('stripe_payment_intent_id', 255)->nullable()->collate(LaravelStripe::ID_DATABASE_COLLATION);
            $table->nullableMorphs('model');
            $table->string('payment_status')->nullable();
            $table->string('mode')->nullable();
            $table->boolean('livemode')->default(false);
            $table->string('currency', 3)->nullable();
            $table->unsignedInteger('amount_subtotal')->nullable();
            $table->unsignedInteger('amount_total')->nullable();
            $table->json('payment_method_types')->nullable();
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
        Schema::dropIfExists('stripe_checkout_sessions');
    }
}
