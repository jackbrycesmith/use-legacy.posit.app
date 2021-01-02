<?php

use CloudCreativity\LaravelStripe\LaravelStripe;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_customers', function (Blueprint $table) {
            $table->string('id', 255)->primary()->collate(LaravelStripe::ID_DATABASE_COLLATION);
            $table->string('stripe_account_id', 255)->nullable()->collate(LaravelStripe::ID_DATABASE_COLLATION);
            $table->nullableMorphs('model');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('currency', 3)->nullable();
            $table->timestamp('created')->nullable();
            $table->boolean('livemode')->nullable();
            $table->json('sources')->nullable();
            $table->json('payment_methods')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('stripe_customers');
    }
}
