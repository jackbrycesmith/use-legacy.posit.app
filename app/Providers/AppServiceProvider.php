<?php

namespace App\Providers;

use App\Models\Organisation;
use App\Models\OrganisationContact;
use App\Models\OrganisationMember;
use App\Models\Proposal;
use App\Models\ProposalContent;
use App\Models\ProposalUser;
use App\Models\StripeAccount;
use App\Models\StripeCheckoutSession;
use App\Models\StripeCustomer;
use App\Models\StripeEvent;
use App\Models\StripePaymentIntent;
use App\Models\User;
use App\Observers\UserObserver;
use CloudCreativity\LaravelStripe\Facades\Stripe;
use CloudCreativity\LaravelStripe\LaravelStripe;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLaravelStripeConnect();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);

        $this->setRelationMorphMap();

        // \URL::forceScheme('https');
        // if(config('app.env') === 'production') {
        // }
    }

    /**
     * Sets the relation morph map.
     *
     * @return self
     *
     * @see https://laravel.com/docs/7.x/eloquent-relationships#custom-polymorphic-types
     */
    protected function setRelationMorphMap()
    {
        Relation::morphMap([
            'organisation' => Organisation::class,
            'organisation_contact' => OrganisationContact::class,
            'organisation_member' => OrganisationMember::class,
            'proposal' => Proposal::class,
            'proposal_content' => ProposalContent::class,
            'proposal_user' => ProposalUser::class,
            'stripe_account' => StripeAccount::class,
            'stripe_checkout_session' => StripeCheckoutSession::class,
            'stripe_customer' => StripeCustomer::class,
            'stripe_event' => StripeEvent::class,
            'stripe_payment_intent' => StripePaymentIntent::class,
            'user' => User::class,
        ]);

        return $this;
    }

    /**
     * Setup jackbrycesmith/laravel-connect-stripe
     *
     * @return self
     */
    protected function registerLaravelStripeConnect()
    {
        LaravelStripe::withoutMigrations();

        LaravelStripe::connectState(\App\Utils\StripeOauthSessionState::class);

        LaravelStripe::currentOwnerResolver(function (Request $request) {
            return $request->stripeConnectOauthOrg();
        });

        return $this;
    }
}
