<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOfferAcceptedEmail implements ShouldQueue
{
     use  Queueable ,Dispatchable, InteractsWithQueue, SerializesModels;
    public $tries = 3;
public $backoff = 10;

    public function __construct(public $offer) {}

    public function handle()
    {
        Mail::raw(
       "Your offer for project '{$this->offer->project->title}' has been accepted.",
       function ($message) {
        $message->to($this->offer->user->email)
                ->subject('Offer Accepted');
    }
);

    }
}

