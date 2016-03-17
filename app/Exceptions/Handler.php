<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Infrastructure\Message\AlertMessageProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    private $alertMessage;
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * @param AlertMessageProvider $alertMessage
     */
    public function __construct(AlertMessageProvider $alertMessage)
    {
        $this->alertMessage = $alertMessage;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        $this->alertMessage->notifyOfError($e);

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e, 404);
        } elseif ($e instanceof NotFoundHttpException) {
            $message = "The route ({$request->server->get('REQUEST_URI')}) you are looking for does not exist";
            $e = new NotFoundHttpException($message, $e, 404);
            $this->alertMessage->notifyOfError($e);
        } elseif ($e instanceof AuthorizationException) {
            $e = new HttpException(403, $e->getMessage());
        }

        // Force render a json response
        return response()->json([
            'status' => $e->getCode(),
            'message' => $e->getMessage(),
        ], $e->getCode());
    }
}
