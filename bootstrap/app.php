<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Taymon\JWT\Exceptions\TokenExpiredException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {

            dd($e->getMessage());
            // dd($e->getMessage());

            if ($e instanceof QueryException) {
                Log::error('Query exception: ' . $e->getMessage());
                return response()->json(['message' => $e->getMessage(), 'error' => true], 500);
            } elseif ($e instanceof DatabaseConnectionException) {
                return response()->json(['error' => true, 'message' => 'Databse connection Error' . $e->getMessage()], 500);
            } elseif ($e instanceof PDOException) {
                return response()->json(['error' => true, 'message' => ' No connection could be made because the target machine actively refused it'], 500);
            } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
                // dd(get_class($e));
                $previous = $e->getPrevious();
                if ($previous == null) {
                    return response()->json(['error' => true, 'message' => "Unauthorized acces: No Token found"], 401);
                }
                // dd($previous);
                elseif ($previous instanceof Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                    return response()->json(['error' => true, 'message' => "Unauthorized acces: Token expired"], 401);
                } elseif ($previous instanceof Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                    return response()->json(['error' => true, 'message' => "Unauthorized acces: Invalid Token"], 401);
                }
            } elseif ($e instanceof Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->json(['message' => "Item Not found", 'error' => true], 404);
            } elseif ($e instanceof Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {

                return response()->json(['message' => "Route Not found", 'error' => true], 404);
            }
            return response()->json(['error' => true, 'message' => 'Error: ' . $e->getMessage()], 500);
        });
    })->create();
