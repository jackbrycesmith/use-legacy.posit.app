<?php

namespace App\Providers;

use App\Actions\Integrations\Paddle\HandlePaddlePaymentSucceeded;
use App\Actions\Integrations\Stripe\HandleStripeConnectFetchedUserCredentials;
use App\Actions\Integrations\Stripe\Webhooks\HandleConnectAccountUpdated;
use App\Actions\Integrations\Stripe\Webhooks\HandleConnectCheckoutSessionAsyncPaymentFailed;
use App\Actions\Integrations\Stripe\Webhooks\HandleConnectCheckoutSessionAsyncPaymentSucceeded;
use App\Actions\Integrations\Stripe\Webhooks\HandleConnectCheckoutSessionCompleted;
use App\Actions\Integrations\Stripe\Webhooks\HandleConnectPaymentIntentPaymentFailed;
use App\Actions\Integrations\Stripe\Webhooks\HandleConnectPaymentIntentProcessing;
use App\Actions\Integrations\Stripe\Webhooks\HandleConnectPaymentIntentSucceeded;
use CloudCreativity\LaravelStripe\Events\FetchedUserCredentials;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Paddle\Events\PaymentSucceeded as PaddlePaymentSucceeded;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->handleCashierPaddleEvents();

        $this->handleStripeConnectEvents();
    }

    /**
     * Handle events from Paddle integration.
     *
     * @return void
     */
    protected function handleCashierPaddleEvents()
    {
        Event::listen(
            PaddlePaymentSucceeded::class,
            HandlePaddlePaymentSucceeded::class
        );
    }

    /**
     * Handle events from Stripe Connect integration.
     *
     * @return void
     */
    protected function handleStripeConnectEvents()
    {
        Event::listen(
            FetchedUserCredentials::class,
            HandleStripeConnectFetchedUserCredentials::class
        );

        Event::listen(
            'stripe.connect.webhooks:account.updated',
            HandleConnectAccountUpdated::class
        );

        Event::listen(
            'stripe.connect.webhooks:payment_intent.processing',
            HandleConnectPaymentIntentProcessing::class
        );

        Event::listen(
            'stripe.connect.webhooks:payment_intent.succeeded',
            HandleConnectPaymentIntentSucceeded::class
        );

        Event::listen(
            'stripe.connect.webhooks:payment_intent.payment_failed',
            HandleConnectPaymentIntentPaymentFailed::class
        );

        Event::listen(
            'stripe.connect.webhooks:checkout.session.completed',
            HandleConnectCheckoutSessionCompleted::class
        );

        Event::listen(
            'stripe.connect.webhooks:checkout.session.async_payment_succeeded',
            HandleConnectCheckoutSessionAsyncPaymentSucceeded::class
        );

        Event::listen(
            'stripe.connect.webhooks:checkout.session.async_payment_failed',
            HandleConnectCheckoutSessionAsyncPaymentFailed::class
        );

    }
}
