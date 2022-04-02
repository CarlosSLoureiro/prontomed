<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
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
        $this->renderable(function (ValidatorException $e, $request) {
            return response()->json($e->getErrors(), Response::HTTP_BAD_REQUEST);
        });

        $this->renderable(function (RequestException $e, $request) {
            return response()->json(['mensagem' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        });

        $this->renderable(function (LoginException $e, $request) {
            return response()->json(['mensagem' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
        });

        $this->renderable(function (Exception $e, $request) {
            return response()->json(['mensagem' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        });
    }
}
