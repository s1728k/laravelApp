<?php

namespace App\Providers;

// use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

// use App\Services\Auth\JwtGuard;
// use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Passport::routes();
		// Passport::tokensExpireIn(now()->addDays(15));
		// Passport::refreshTokensExpireIn(now()->addDays(30));
        // Auth::extend('jwt', function ($app, $name, array $config) {
        //     // Return an instance of Illuminate\Contracts\Auth\Guard...

        //     return new JwtGuard(Auth::createUserProvider($config['provider']));
        // });
    }
}

// Name
// NAME
// Value

// nodejs-mongo-persistent
// Name
// NAMESPACE
// Value

// openshift
// Name
// MEMORY_LIMIT
// Value

// 512Mi
// Name
// MEMORY_MONGODB_LIMIT
// Value

// 512Mi
// Name
// VOLUME_CAPACITY
// Value

// 1Gi
// Name
// SOURCE_REPOSITORY_URL
// Value

// https://github.com/openshift/nodejs-ex.git
// Name
// SOURCE_REPOSITORY_REF
// Value

// Name
// CONTEXT_DIR
// Value

// Name
// APPLICATION_DOMAIN
// Value

// Name
// GITHUB_WEBHOOK_SECRET
// Value

// e3vbXHW6rFGF1yDhXm8EaEjgVMdyNNC4BeajCxGV
// Name
// GENERIC_WEBHOOK_SECRET
// Value

// aNTBYjVdSu3th2syjYWu3PagbsEQrVwdxglxukg3
// Name
// DATABASE_SERVICE_NAME
// Value

// mongodb
// Name
// DATABASE_USER
// Value

// userU5R
// Name
// DATABASE_PASSWORD
// Value

// Q5A5vUvoEuBFkEV1
// Name
// DATABASE_NAME
// Value

// sampledb
// Name
// DATABASE_ADMIN_PASSWORD
// Value

// s2snsoJ1nuHYhyTT
// Name
// NPM_MIRROR
// Value
