<?php
namespace Infrastructure;

use Laravel\Lumen\Application as LumenApplication;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Application extends LumenApplication
{
    /**
     * @return $this|\Monolog\Handler\HandlerInterface
     */
    protected function getMonologHandler()
    {
        // Return parent if in development|testing mode
        if (env('APP_ENV') !== 'production') {
            // Error log is default (/app/storage/logs/lumen.log)
            return parent::getMonologHandler();
        }

        return (new StreamHandler(env('APP_LOG_PATH', 'var/log/nginx/lumen.log'), Logger::DEBUG))
            ->setFormatter(new LineFormatter(null, null, true, true));
    }
}
