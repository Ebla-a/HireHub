<?php

namespace App\Jobs;

use App\Models\FreelancerProfile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class RecalculateFreelancerRating implements ShouldQueue
{
    use  Queueable ,Dispatchable, InteractsWithQueue, SerializesModels;

    public $tries = 3;
    public $backoff = 10;

    public function __construct(public $freelancerId) {}

   public function handle()
{
    $lock = Cache::lock("rating_lock_{$this->freelancerId}", 5);

    if ($lock->get()) {

        $profile = FreelancerProfile::find($this->freelancerId);
        $avg = $profile->reviews()->avg('rating');
        $profile->update(['rating' => $avg]);

        $lock->release();
    }
}

}
