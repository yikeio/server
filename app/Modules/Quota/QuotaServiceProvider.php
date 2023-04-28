<?php

namespace App\Modules\Quota;

use App\Modules\Quota\Policies\QuotaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class QuotaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Gate::policy(Quota::class, QuotaPolicy::class);

        QuotaRouteRegistrar::all();
    }
}
