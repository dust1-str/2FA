<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (QueryException $e, $request) {
            if ($this->isDatabaseConnectionError($e)) {
                Log::error('Database connection error: ' . $e->getMessage());
                return response()->view('errors.database', [], Response::HTTP_SERVICE_UNAVAILABLE);
            }
        });
    }

    /**
     * Determine if the exception is a database connection error.
     *
     * @param \Illuminate\Database\QueryException $e
     * @return bool
     */
    protected function isDatabaseConnectionError(QueryException $e)
    {
        $message = $e->getMessage();
        return str_contains($message, 'SQLSTATE[08006]');
    }
}
