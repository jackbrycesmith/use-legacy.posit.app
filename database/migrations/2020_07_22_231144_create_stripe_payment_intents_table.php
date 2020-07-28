<?php

use CloudCreativity\LaravelStripe\LaravelStripe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripePaymentIntentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_payment_intents', function (Blueprint $table) {
            $table->string('id', 255)->primary()->collate(LaravelStripe::ID_DATABASE_COLLATION);
            $table->string('stripe_account_id', 255)->nullable()->collate(LaravelStripe::ID_DATABASE_COLLATION);
            $table->string('stripe_customer_id', 255)->nullable()->collate(LaravelStripe::ID_DATABASE_COLLATION);
            $table->nullableMorphs('model');
            $table->unsignedInteger('amount')->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('status')->nullable();
            $table->string('setup_future_usage')->nullable();
            $table->boolean('livemode')->nullable();
            $table->timestamp('canceled_at')->nullable();
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
        Schema::dropIfExists('stripe_payment_intents');
    }
}
