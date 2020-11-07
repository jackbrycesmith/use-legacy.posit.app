<?php

namespace App\Providers;

use App\Models\Proposal;
use App\Models\ProposalContent;
use App\Models\ProposalPayment;
use App\Models\ProposalUser;
use App\Models\StripeAccount;
use App\Models\StripeCheckoutSession;
use App\Models\StripeCustomer;
use App\Models\StripeEvent;
use App\Models\StripePaymentIntent;
use App\Models\Team;
use App\Models\TeamContact;
use App\Models\TeamMember;
use App\Models\User;
use App\Observers\ProposalObserver;
use App\Observers\TeamContactObserver;
use App\Observers\UserObserver;
use CloudCreativity\LaravelStripe\LaravelStripe;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
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
        $this->setModelObservers();
        $this->setRelationMorphMap();

        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }
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
            'team' => Team::class,
            'team_contact' => TeamContact::class,
            'team_member' => TeamMember::class,
            'proposal' => Proposal::class,
            'proposal_content' => ProposalContent::class,
            'proposal_payment' => ProposalPayment::class,
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
     * Sets the event observers for models.
     *
     * @return self ( description_of_the_return_value )
     */
    protected function setModelObservers()
    {
        Proposal::observe(ProposalObserver::class);
        User::observe(UserObserver::class);
        TeamContact::observe(TeamContactObserver::class);

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
            return $request->stripeConnectOauthTeam();
        });

        return $this;
    }
}
