<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendProjectCreatedEmail implements ShouldQueue
{
     use  Queueable ,Dispatchable, InteractsWithQueue, SerializesModels;
    public $tries = 3;
public $backoff = 10;

    public function __construct(public $project) {}

public function handle()
{
    Mail::raw(
        "Your project '{$this->project->title}' has been published.",
        function ($message) {
            $message->to($this->project->user->email)
                    ->subject('Project Published');
        }
    );
}
}

