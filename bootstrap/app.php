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
    ->withMiddleware(function (Middleware $middleware) {
        // Enregistrer les middleware de rôles
        $middleware->alias([
            'admin'      => \App\Http\Middleware\CheckAdmin::class,
            'medecin'    => \App\Http\Middleware\CheckMedecin::class,
            'secretaire' => \App\Http\Middleware\CheckSecretaire::class,
            'patient'    => \App\Http\Middleware\CheckPatient::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();