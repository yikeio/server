<?php

namespace App\Modules\Quota;

use Illuminate\Support\ServiceProvider;

class QuotaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        QuotaRouteRegistrar::all();
    }
}
