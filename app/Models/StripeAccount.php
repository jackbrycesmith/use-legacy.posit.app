<?php

namespace App\Models;

use CloudCreativity\LaravelStripe\Models\StripeAccount as LaravelStripeAccount;
use Stripe\Account;
use Stripe\StripeObject;

class StripeAccount extends LaravelStripeAccount
{
    protected $casts = [
        'business_profile' => 'array',
        'capabilities' => 'array',
        'charges_enabled' => 'boolean',
        'details_submitted' => 'boolean',
        'individual' => 'array',
        'metadata' => 'array',
        'payouts_enabled' => 'boolean',
        'requirements' => 'array',
        'settings' => 'array',
        'tos_acceptance' => 'json',
    ];

    protected $dates = [
        'deleted_at',
        'created'
    ];

    /**
     * Fetch account from Stripe API & save to model
     *
     * @param boolean $shouldUpdate (only intended to be disabled for test purposes)
     *
     * @return self
     */
    public function updateFromStripeApi(bool $shouldUpdate = true)
    {
        $stripeAccount = $this->stripe()->accounts()->retrieve();

        if ($shouldUpdate) {
            $this->fillFrom($stripeAccount);
            $this->save();
        }

        return $this;
    }

    /**
     * Fill the model attributes from the Stripe Account API object
     *
     * @param \Stripe\Account $stripeAccount The stripe account
     *
     * @throws \InvalidArgumentException if account id mismatch
     *
     * @return self
     */
    public function fillFrom(Account $stripeAccount)
    {
        $accountId = data_get($stripeAccount, 'id');

        if (!is_null($this->id) && $this->id !== $accountId) {
            throw new \InvalidArgumentException("Stripe account id update mismatch: current: {$this->id}, update: {$accountId}");
        }

        $this->id = data_get($stripeAccount, 'id');
        $this->type = data_get($stripeAccount, 'type');
        $this->payouts_enabled = (bool) data_get($stripeAccount, 'payouts_enabled');
        $this->email = data_get($stripeAccount, 'email');
        $this->country = data_get($stripeAccount, 'country');
        $this->default_currency = data_get($stripeAccount, 'default_currency');
        $this->details_submitted = (bool) data_get($stripeAccount, 'details_submitted');
        $this->charges_enabled = (bool) data_get($stripeAccount, 'charges_enabled');

        $this->settings = optional(data_get($stripeAccount, 'settings'), function ($stripeObj) {
            return $stripeObj->toArray();
        });

        $this->requirements = optional(data_get($stripeAccount, 'requirements'), function ($stripeObj) {
            return $stripeObj->toArray();
        });

        $this->business_profile = optional(data_get($stripeAccount, 'business_profile'), function ($stripeObj) {
            return $stripeObj->toArray();
        });

        $this->capabilities = optional(data_get($stripeAccount, 'capabilities'), function ($stripeObj) {
            return $stripeObj->toArray();
        });

        $this->metadata = optional(data_get($stripeAccount, 'metadata'), function ($stripeObj) {
            return $stripeObj->toArray();
        });

        $this->individual = optional(data_get($stripeAccount, 'individual'), function ($stripeObj) {
            return $stripeObj->toArray();
        });

        return $this;
    }

    /**
     * Creates a new account via stripe api & saves to db.
     *
     * @param integer|null $ownerId The owner identifier (e.g. organisation id)
     * @param string $type The stripe account type
     * @param array $params Any stripe api account creation parameters
     *
     * @return StripeAccount The created stripe account.
     */
    public static function createFromStripeApi(?int $ownerId = null, string $type = 'standard', array $params = []): StripeAccount
    {
        $stripeApiAccount = app('stripe')->account()->accounts()->create($type, $params);
        $stripeAccount = (new StripeAccount)->fillFrom($stripeApiAccount);
        $stripeAccount->owner_id = $ownerId;
        $stripeAccount->save();

        return $stripeAccount;
    }
}
