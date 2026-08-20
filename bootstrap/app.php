<?php

use App\Http\Middleware\ForceToHttps;
use Illuminate\Foundation\Application;
use App\Http\Middleware\OwnerMiddleware;
use App\Http\Middleware\PegawaiMiddleware;
use App\Http\Middleware\RedirecIfAuthenticated;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'guest' => RedirecIfAuthenticated::class,
            'owner' => OwnerMiddleware::class,
            'pegawai' => PegawaiMiddleware::class
        ]);
        $middleware->append(ForceToHttps::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
