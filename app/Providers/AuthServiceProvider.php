<?php

namespace App\Providers;

use App\Models\Item;
use App\Models\Request;
use App\Models\User;
use App\Policies\ItemPolicy;
use App\Policies\RequestsPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Request::class => RequestsPolicy::class,
        Item::class => ItemPolicy::class,
        User::class => UserPolicy::class
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
