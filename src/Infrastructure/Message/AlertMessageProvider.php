<?php
namespace Infrastructure\Message;

use Exception;
use Illuminate\Queue\Events\JobFailed;

interface AlertMessageProvider
{
    public function notifyOfError(Exception $e);

    public function notifyQueueFailure(JobFailed $event);
}
