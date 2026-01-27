<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
            $this->logWithContext($e);
        });
    }

    /**
     * Log exception with additional context
     */
    protected function logWithContext(Throwable $exception): void
    {
        $context = [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'message' => $exception->getMessage(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id(),
        ];

        // Add request data for non-production
        if (!app()->environment('production')) {
            $context['request_data'] = request()->except($this->dontFlash);
        }

        // Determine log level based on exception type
        $level = match(true) {
            $exception instanceof ValidationException => 'info',
            $exception instanceof AuthenticationException => 'warning',
            $exception instanceof HttpException && $exception->getStatusCode() < 500 => 'warning',
            default => 'error',
        };

        Log::$level($exception->getMessage(), $context);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Return JSON for API requests
        if ($request->expectsJson()) {
            return $this->renderJsonResponse($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Render exception as JSON response
     */
    protected function renderJsonResponse($request, Throwable $exception)
    {
        $status = $this->getExceptionHttpStatusCode($exception);
        
        $response = [
            'error' => true,
            'message' => $exception->getMessage(),
        ];

        // Add more details in non-production
        if (!app()->environment('production')) {
            $response['exception'] = get_class($exception);
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
            $response['trace'] = $exception->getTrace();
        }

        return response()->json($response, $status);
    }

    /**
     * Get HTTP status code from exception
     */
    protected function getExceptionHttpStatusCode(Throwable $exception): int
    {
        return match(true) {
            $exception instanceof ValidationException => 422,
            $exception instanceof AuthenticationException => 401,
            $exception instanceof HttpException => $exception->getStatusCode(),
            default => 500,
        };
    }
}
