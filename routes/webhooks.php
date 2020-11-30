<?php

use App\Actions\Integrations\Aws\HandleSesFeedback;
use App\Http\Controllers\PaddleWebhookController;
use CloudCreativity\LaravelStripe\Facades\Stripe;
use Illuminate\Support\Facades\Route;

Stripe::webhook('/stripe/webhooks/connect', 'connect');

Route::post('/paddle/webhook', PaddleWebhookController::class)->name('cashier.webhook');

Route::post('/aws-ses-feedback', HandleSesFeedback::class);
