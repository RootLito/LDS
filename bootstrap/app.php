<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function ($request) {
            // If the URL contains 'employee', go to employee login
            if ($request->is('employee/*') || $request->is('employee')) {
                return route('employee.login.form');
            }

            // Default for everything else (like /admin/*)
            return route('admin.login.form');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
