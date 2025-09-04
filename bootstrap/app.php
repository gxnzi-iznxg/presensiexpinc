<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn (string $guard) => match($guard) { 

            'user' => '/panel', // Jika guard 'user' belum login, arahkan ke /panel 
            'karyawan' => '/', // Jika guard 'karyawan' belum login, arahkan ke / 

        default => '/', 

    });
    // Pastikan alias 'guest' mengarah ke middleware Anda

        $middleware->alias([

        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
