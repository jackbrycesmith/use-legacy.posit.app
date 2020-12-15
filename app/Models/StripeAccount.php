<?php

namespace App\Models;

use CloudCreativity\LaravelStripe\Models\StripeAccount as LaravelStripeAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Stripe\Account;
use Stripe\StripeObject;

class StripeAccount extends LaravelStripeAccount
{
    use HasFactory;

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
     * Get the stored stripe customers of this account.
     *
     * @return HasMany The has many relationship.
     */
    public function stripeCustomers(): HasMany
    {
        return $this->hasMany(StripeCustomer::class, 'stripe_account_id');
    }

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
     * Determines if account has a given capability.
     *
     * @param string $capability The capability
     *
     * @return boolean True if has active capability, False otherwise.
     *
     * @see https://stripe.com/docs/api/accounts/object?lang=php#account_object-capabilities
     */
    public function hasCapability(string $capability): bool
    {
        return Arr::get($this->capabilities, $capability) === 'active';
    }

    /**
     * Get the account link type for this account.
     *
     * @return string The stripe account link type
     *
     * @see https://stripe.com/docs/api/account_links/create#create_account_link-type
     */
    public function accountLinkType(): string
    {
        return 'account_onboarding'; // Standard accounts only support 'account_onboarding'// $this->details_submitted ? 'account_update' : 'account_onboarding';
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
        $this->business_type = data_get($stripeAccount, 'business_type');
        $this->email = data_get($stripeAccount, 'email');
        $this->country = data_get($stripeAccount, 'country');
        $this->default_currency = data_get($stripeAccount, 'default_currency');
        $this->details_submitted = (bool) data_get($stripeAccount, 'details_submitted');
        $this->charges_enabled = (bool) data_get($stripeAccount, 'charges_enabled');

        $this->settings = optional(data_get($stripeAccount, 'settings'), function ($stripeObj) {
            if (is_array($stripeObj)) return $stripeObj;
            return $stripeObj->toArray();
        });

        $this->requirements = optional(data_get($stripeAccount, 'requirements'), function ($stripeObj) {
            if (is_array($stripeObj)) return $stripeObj;
            return $stripeObj->toArray();
        });

        $this->business_profile = optional(data_get($stripeAccount, 'business_profile'), function ($stripeObj) {
            if (is_array($stripeObj)) return $stripeObj;
            return $stripeObj->toArray();
        });

        $this->capabilities = optional(data_get($stripeAccount, 'capabilities'), function ($stripeObj) {
            if (is_array($stripeObj)) return $stripeObj;
            return $stripeObj->toArray();
        });

        $this->metadata = optional(data_get($stripeAccount, 'metadata'), function ($stripeObj) {
            if (is_array($stripeObj)) return $stripeObj;
            return $stripeObj->toArray();
        });

        $this->individual = optional(data_get($stripeAccount, 'individual'), function ($stripeObj) {
            if (is_array($stripeObj)) return $stripeObj;
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

    /**
     * Get the payment method types for a stripe checkout session (all that are
     * active for the account)
     *
     * @param string $currencyCode
     *
     * @return array
     */
    public function checkoutSessionPaymentMethodTypes(string $currencyCode): array
    {
        $paymentMethods = $this->validPaymentMethodsForCurrency($currencyCode);

        return collect($paymentMethods)
            ->filter(fn($value) => $this->hasCapability("{$value}_payments"))
            ->values()
            ->toArray();
    }

    /**
     * Get the available payment methods for the given currency.
     *
     * @param string $currencyCode The currency code
     *
     * @return array
     *
     * @see https://stripe.com/en-gb/payments/payment-methods-guide#payment-methods-fact-sheets
     */
    public static function validPaymentMethodsForCurrency(string $currencyCode): array
    {
        $currencyCode = Str::lower($currencyCode);

        $defaultPaymentMethods = [
            'card'
        ];

        $paymentMethods = [];

        switch ($currencyCode) {
            case "eur":
                $paymentMethods = [
                    'sepa_debit',
                    'bancontact',
                    'eps',
                    'giropay',
                    'ideal',
                    'sofort',
                ];
                break;
            case "gbp":
                $paymentMethods = [
                    'bacs_debit'
                ];
                break;
            case "pln":
                $paymentMethods = [
                    'p24'
                ];
                break;
        }

        return array_merge($defaultPaymentMethods, $paymentMethods);
    }

    /**
     * Makes a stripe checkout session from their api.
     *
     * @param array $params The parameters
     *
     * @return \Stripe\Checkout\Session the api response
     */
    public function makeStripeCheckoutSession(array $params): \Stripe\Checkout\Session
    {
         $checkoutSessionResponse = $this->stripe()->checkoutSessions()->create($params);

         // TODO better?
         return $checkoutSessionResponse;
    }
}
