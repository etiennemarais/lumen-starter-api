<?php
namespace Infrastructure;

use Illuminate\Log\Writer;
use Laravel\Lumen\Application as LumenApplication;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Application extends LumenApplication
{
    // Added for some laravel packages needs this version apparently.
    const VERSION = '5';

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

    /**
     * Register container log bindings for the application.
     *
     * @return void
     */
    protected function registerLogBindings()
    {
        $this->singleton('Psr\Log\LoggerInterface', function () {
            if ($this->monologConfigurator) {
                $monolog = call_user_func($this->monologConfigurator, new Logger('lumen'));
            } else {
                $monolog = new Logger('lumen', [$this->getMonologHandler()]);
            }

            return new Writer($monolog);
        });
    }
}
