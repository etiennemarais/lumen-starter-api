<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Infrastructure\Traits\SerializesDeletedModels;

abstract class Job implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, SerializesDeletedModels {
        SerializesDeletedModels::getRestoredPropertyValue insteadof SerializesModels;
    }
}
