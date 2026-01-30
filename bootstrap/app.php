<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // This replaces the old Authenticate middleware redirect logic
        $middleware->redirectGuestsTo(function (Request $request) {
            // If the user is trying to access an admin route, send to admin login
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login.form');
            }

            // Otherwise, default to employee login
            return route('employee.login.form');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();