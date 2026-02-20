<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log all exceptions securely
            \Log::error('Application Exception', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        });

        $this->renderable(function (Throwable $e, $request) {
            // Render user-friendly error pages instead of exposing debug info
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'An error occurred. Please try again later.',
                ], 500);
            }

            // For HTTP exceptions, render appropriate error view
            if ($this->isHttpException($e)) {
                $statusCode = $e->getStatusCode();
                $viewPath = "errors.{$statusCode}";
                
                if (view()->exists($viewPath)) {
                    return response()->view($viewPath, [], $statusCode);
                }
            }

            // Generic server error for unhandled exceptions
            if (config('app.debug')) {
                return null; // Let Laravel's default handler take over in debug mode
            }

            return response()->view('errors.500', [], 500);
        });
    }
}

