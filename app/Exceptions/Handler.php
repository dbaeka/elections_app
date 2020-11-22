<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            //

        });
    }

    public function report(Throwable $e)
    {
        parent::report($e); // TODO: Change the autogenerated stub
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof QueryException || $e instanceof ModelNotFoundException) {
            if (Str::contains($e->getMessage(), 'duplicate key value')) {
                return $this->queryJsonResponse($request, $e);
            } else
                $e = new NotFoundHttpException('Resource not found');
        } else if ($e instanceof RouteNotFoundException) {
            $e = new AuthenticationException('Lack permissions');
        }

        return parent::render($request, $e); // TODO: Change the autogenerated stub
    }

    protected function queryJsonResponse($request, Throwable $e)
    {
        return response()->json([
            'errors' => [
                [
                    'title' => Str::title(Str::snake(class_basename(
                        $e), ' ')),
                    'details' => 'DB Error: Duplicate value where unique', //$e->getMessage(),
                ]]
        ], 409);
    }

    protected function prepareJsonResponse($request, Throwable $e)
    {
        return response()->json([
            'errors' => [
                [
                    'title' => Str::title(Str::snake(class_basename(
                        $e), ' ')),
                    'details' => $e->getMessage(),
                ]]
        ], $this->isHttpException($e) ? $e->getStatusCode() : 500);
    }

    protected function invalidJson($request, ValidationException $e)
    {
        $errors = (new Collection($e->validator->errors()))
            ->map(function ($error, $key) {
                return [
                    'title' => 'Validation Error',
                    'details' => $error[0],
                    'source' => [
                        'pointer' => '/' . str_replace('.', '/', $key),
                    ]
                ];
            })
            ->values();

        return response()->json([
            'errors' => $errors
        ], $e->status);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'errors' => [
                    [
                        'title' => 'Unauthenticated',
                        'details' => 'You are not authenticated',
                    ]]
            ], 403);
        } else {
            return response()->json([
                'errors' => [
                    [
                        'title' => 'Header Wrong or Missing Attribute',
                        'details' => 'Expecting json request [accept: application/vnd.api+json]',
                    ]]
            ], 403);
        }
//        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
