<?php

namespace App\Modules\Auth;

use App\Modules\Auth\Endpoints\CreateTokenViaCode;
use App\Modules\Auth\Endpoints\CreateTokenViaSms;
use App\Modules\Auth\Endpoints\Redirect;
use Illuminate\Support\Facades\Route;

class AuthRouteRegistrar
{
    public static function all()
    {
        Route::group([
            'middleware' => ['api'],
            'prefix' => 'api',
        ], function () {
            Route::post('/auth/tokens:via-sms', CreateTokenViaSms::class)->middleware('throttle:60,1');
            Route::post('/auth/tokens:via-code', CreateTokenViaCode::class)->middleware('throttle:60,1');
            Route::get('/auth/redirect', Redirect::class)->middleware('throttle:120,1');
        });

        Route::group([
            'middleware' => ['api'],
            'as' => 'passport.',
            'prefix' => 'oauth',
            'namespace' => 'Laravel\Passport\Http\Controllers',
        ], function () {
            Route::post('/token', ['uses' => 'AccessTokenController@issueToken', 'as' => 'token'])->middleware('throttle:60,1');

            Route::group([
                'middleware' => ['auth'],
            ], function () {
                Route::post('/clients', ['uses' => 'ClientController@store', 'as' => 'clients.store'])->middleware('throttle:60,1');
                Route::delete('/clients/{client_id}', ['uses' => 'ClientController@destroy', 'as' => 'clients.destroy'])->middleware('throttle:60,1');
                Route::get('/clients', ['uses' => 'ClientController@forUser', 'as' => 'clients.index'])->middleware('throttle:120,1');
            });
        });
    }
}
