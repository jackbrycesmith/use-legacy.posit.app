<?php

namespace App\Providers;

use App\Models\Posit;
use App\Models\PositPayment;
use App\Models\PositUser;
use App\Models\StripeAccount;
use App\Models\StripeCheckoutSession;
use App\Models\StripeCustomer;
use App\Models\StripeEvent;
use App\Models\StripePaymentIntent;
use App\Models\Team;
use App\Models\TeamContact;
use App\Models\TeamMember;
use App\Models\User;
use App\Observers\PositObserver;
use App\Observers\TeamContactObserver;
use App\Observers\UserObserver;
use App\Utils\BladeRouteGenerator;
use App\Utils\Paddle;
use CloudCreativity\LaravelStripe\LaravelStripe;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Laravel\Paddle\Cashier;
use Laravel\Paddle\Receipt;

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
        $this->registerCashierPaddle();
        $this->registerDatabaseEncryptionKey();
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
        $this->setPaddleProducts();
        $this->bootBlade();

        // if(config('app.env') === 'production') {
        //     \URL::forceScheme('https');
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
            'team' => Team::class,
            'team_contact' => TeamContact::class,
            'team_member' => TeamMember::class,
            'paddle_receipt' => Receipt::class,
            'posit' => Posit::class,
            'posit_payment' => PositPayment::class,
            'posit_user' => PositUser::class,
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
        Posit::observe(PositObserver::class);
        User::observe(UserObserver::class);
        TeamContact::observe(TeamContactObserver::class);

        return $this;
    }

    /**
     * Sets the paddle products.
     *
     * @return self
     */
    protected function setPaddleProducts()
    {
        Paddle::product([
            'product_id' => 637680
        ])->credits(50);

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

    /**
     * Setup laravel/cashier-paddle
     *
     * @see https://github.com/laravel/cashier-paddle
     * @return self
     */
    protected function registerCashierPaddle()
    {
        Cashier::ignoreMigrations();

        Cashier::ignoreRoutes();

        return $this;
    }

    /**
     * Setup separate encryption key for eloquent models
     *
     * @return self
     */
    protected function registerDatabaseEncryptionKey()
    {
        $databaseEncryptionKey = config('database.encryption_key');
        $encrypter = new Encrypter($databaseEncryptionKey, 'AES-256-CBC');
        Model::encryptUsing($encrypter);

        return $this;
    }

    /**
     * Boot any custom blade helpers
     *
     * @return self
     */
    protected function bootBlade()
    {
        Blade::directive('routes_with_url', function ($group) {
            return "<?php echo app('" . BladeRouteGenerator::class . "')->generate({$group}); ?>";
        });

        return $this;
    }
}
