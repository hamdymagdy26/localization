<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(
                [
                    'data' => [],
                    'message' => 'Record is not found',
                    'success' => false
                ], 404);
        }
        if ($exception instanceof NotFoundHttpException) {
            return response()->json(
                [
                    'data' => [],
                    'message' => 'Route is not found',
                    'success' => false
                ], 404);
        }
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json(
                [
                    'data' => [],
                    'message' => $exception->getMessage(),
                    'success' => false
                ], 405);
        }
        if ($exception instanceof QueryException) {
            if ($exception->getCode() === "23000") {
                return response()->json(
                    [
                        'data' => [],
                        'message' => "Cannot delete or update a parent item",
                        'success' => false
                    ], 405
                );
            }
        }
        return parent::render($request, $exception);
    }
}
