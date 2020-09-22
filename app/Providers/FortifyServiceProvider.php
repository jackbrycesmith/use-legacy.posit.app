<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Fortify::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(fn() => inertia('Auth/Login')->toResponse(request()));
        Fortify::registerView(fn() => inertia('Auth/Register')->toResponse(request()));
        Fortify::verifyEmailView(fn() => inertia('Auth/VerifyEmail')->toResponse(request()));
        Fortify::requestPasswordResetLinkView(fn() => inertia('Auth/ForgotPassword')->toResponse(request()));

        Route::group([
            'namespace' => 'Laravel\Fortify\Http\Controllers',
            'domain' => config('fortify.domain', null),
            'prefix' => config('fortify.path'),
        ], function () {
            $this->loadRoutesFrom(base_path('routes/fortify.php'));
        });
    }
}
