<?php

use App\Http\Middleware\CheckIsAdmin;
use App\Http\Middleware\EnsureFreelancerIsVerified;
use App\Http\Middleware\PerformanceMonitor;
use App\Http\Middleware\userIsClient;
use App\Http\Middleware\userIsFreelancer;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'verified.freelancer' => EnsureFreelancerIsVerified::class,
            'role.client'     => userIsClient::class,
           'role.freelancer' => userIsFreelancer::class,
           'Performance.Monitor' =>PerformanceMonitor::class,
           'is_admin' => CheckIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //  
    })->create();
