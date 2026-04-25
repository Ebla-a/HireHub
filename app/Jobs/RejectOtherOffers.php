<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
class RejectOtherOffers implements ShouldQueue
{
     use  Queueable ,Dispatchable, InteractsWithQueue, SerializesModels;
    public $tries = 3;
   public $backoff = 10;

    public function __construct(public $project) {}

    public function handle()
    {
        
        $this->project->offers()
            ->where('id', '!=', $this->project->accepted_offer_id)
            ->update(['status' => 'rejected']);
    }
}

