<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        $this->renderable(function (UnauthorizedException $e) {
            return response()->json([
                'message' => 'Unauthorized',
                'status_code' => 403,
            ], 403);
        });

        $this->renderable(function (AuthenticationException|AuthorizationException $e) {
            return response()->json([
                'status_code'   => 401,
                'message'       => trans($e->getMessage()),
            ], 401);
        });
    }
}
