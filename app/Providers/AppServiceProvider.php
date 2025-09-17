<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia as InertiaAssert;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        TestResponse::macro('assertComponent', function (string $component, array $props = []) {
            /** @var \Illuminate\Testing\TestResponse $this */
            return InertiaAssert::fromTestResponse($this)->component($component, $props);
        });

        TestResponse::macro('assertHasPaginatedResource', function (string $resourceName, $resourceClass) {
            /** @var \Illuminate\Testing\TestResponse $this */
            return InertiaAssert::fromTestResponse($this)->hasPaginatedResource($resourceName, $resourceClass);
        });
    }
}
