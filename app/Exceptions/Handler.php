<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use lygav\slackbot\SlackBot;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

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
        Log::error(Exception::class . ": " . $e->getMessage() . " (code:{$e->getCode()})");
        if (in_array(env('APP_ENV'), ['production', 'staging'])) {
            $options = array(
                'username' => env('SLACK_BOT_NAME', 'lumen-starter-bot'),
                'icon_emoji' => env('SLACK_BOT_EMOJI' ,':mushroom:'),
                'channel' => env('SLACK_ERROR_CHANNEL'),
            );
            $bot = new Slackbot(env('SLACK_WEBHOOK_URL'), $options);
            $attachment = $bot->buildAttachment(Exception::class . ": " . $e->getMessage()/* mandatory by slack */)
                ->setPretext("Something went wrong in your lumen api")
                ->setText(Exception::class . ": " . $e->getMessage() . " (code:{$e->getCode()})")
                ->setColor("red");

            $bot->attach($attachment)->send();
        }
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
        return parent::render($request, $e);
    }
}
