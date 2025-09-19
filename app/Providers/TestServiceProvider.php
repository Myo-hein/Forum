<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use Inertia\Testing\AssertableInertia;

class TestServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (! $this->app->runningUnitTests()) {
            return;
        }

        AssertableInertia::macro('hasResource', function (string $key, JsonResource $resource) {
            $this->has($key);

            $actual = $this->prop($key);
            $expected = $resource->response()->getData(true);

            expect($actual)->toEqual($expected);
            return $this;
        });

        AssertableInertia::macro('hasPaginatedResource', function (string $key, ResourceCollection $resource) {
            $this->has("{$key}.data");
            $this->has("{$key}.links");
            $this->has("{$key}.meta");

            $actualData = $this->prop("{$key}.data");
            $expectedResponse = $resource->response()->getData(true);
            $expectedData = $expectedResponse['data'] ?? $expectedResponse;

            expect($actualData)->toEqual($expectedData);
            return $this;
        });

        TestResponse::macro('assertHasResource', function (string $key, JsonResource $resource) {
            return $this->assertInertia(fn (AssertableInertia $inertia) => $inertia->hasResource($key, $resource));
        });

        TestResponse::macro('assertHasPaginatedResource', function (string $key, ResourceCollection $resource) {
            return $this->assertInertia(fn (AssertableInertia $inertia) => $inertia->hasPaginatedResource($key, $resource));
        });

        TestResponse::macro('assertComponent', function (string $component) {
            return $this->assertInertia(fn (AssertableInertia $inertia) => $inertia->component($component, true));
        });
    }
}

