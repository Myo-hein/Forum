<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
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

        Relation::enforceMorphMap([
            'post' => Post::class,
            'comment' => Comment::class,
            'user' => User::class
        ]);
    }
}
