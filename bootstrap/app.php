<?php

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
    ->withMiddleware(function (Middleware $middleware) {
        // Enregistre le middleware CORS natif de Laravel pour toutes les requêtes API
        $middleware->append(\Illuminate\Http\Middleware\HandleCors::class);

        // Exclut les routes API de la vérification CSRF
        $middleware->validateCsrfTokens(except: [
            'api/*',
            '*',
        ]);
        
        // ⚠️ Décommente la ligne ci-dessous SEULEMENT si tu utilises des cookies Sanctum. 
        // Comme tu utilises du localStorage avec 'Bearer token', il faut la retirer/commenter :
        // $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();