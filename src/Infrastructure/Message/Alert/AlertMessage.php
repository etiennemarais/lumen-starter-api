<?php
namespace Infrastructure\Message\Alert;

use Exception;
use Illuminate\Queue\Events\JobFailed;
use Infrastructure\Message\AlertMessageProvider;

class AlertMessage implements AlertMessageProvider
{
    private $messageBot;

    /**
     * @param mixed $messageBot
     */
    public function __construct($messageBot)
    {
        $this->messageBot = $messageBot;
    }

    /**
     * @param Exception $e
     */
    public function notifyOfError(Exception $e)
    {
        $this->messageBot->attach([
            'fallback' => get_class($e) . ": " . $e->getMessage(), # mandatory by slack
            'pretext' => "New error alert from Starter",
            'text' => get_class($e) . ": " . $e->getMessage() . " (code: {$e->getCode()})",
            'color' => "#FF5722",
        ])->send();
    }

    /**
     * @param JobFailed $event
     */
    public function notifyQueueFailure(JobFailed $event)
    {
        $readableData = $event->data['data']['command'];

        $attachment = [
            'fallback' => "Your queue is not feeling well", # mandatory by slack
            'pretext' => "Your queue is not feeling well",
            'text' => "Jobs are currently failing:\n\n$readableData\n",
            'color' => "#FF8A65",
        ];

        $this->messageBot->attach($attachment)
            ->enableMarkdown()
            ->send();
    }
}
