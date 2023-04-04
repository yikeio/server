<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $routes = [];

    /** @var \Illuminate\Routing\Route $route */
    foreach (Route::getRoutes()->getIterator() as $route) {
        if (! Str::startsWith($route->uri, 'api/') || Str::startsWith($route->uri, 'api/admin')) {
            continue;
        }

        $reflectionClass = new reflectionClass($route->getControllerClass());

        $request = Arr::first($reflectionClass->getMethod('__invoke')->getParameters());

        if ($request) {
            $request = (new reflectionClass($request->getType()->getName()))->newInstance();
        }

        $routes[Arr::last(explode('\\', $route->getActionName()))] = [
            'uri' => $route->uri,
            'method' => Arr::first($route->methods),
            'parameters' => method_exists($request, 'rules') ? $request->rules() : [],
        ];
    }

    return [
        'name' => 'Yike API',
        'base_uri' => url('api'),
        'api' => $routes,
    ];
});
