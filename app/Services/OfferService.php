<?php 

namespace App\Services;

use App\Jobs\RejectOtherOffers;
use App\Jobs\SendOfferAcceptedEmail;
use App\Models\Offer;
use App\Models\Project;
use App\Notifications\OfferAcceptedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OfferService 
{
public function getProjectOffers(Project $project)
{

    if (auth('sanctum')->id() !== $project->user_id) {
        abort(403, 'Unauthorized action.');
    }

    return $project->offers()
        ->with(['user.profile']) 
        ->latest()
        ->get();
}


/**
 * create new offer 
 * @param array $data
 * @return Offer
 */
public function storeOffer(array $data)
    {
       
        $exists = Offer::where('project_id', $data['project_id'])
            ->where('user_id', auth('sanctum')->id())
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'project_id' => 'you have aplied this offer before' 
            ]);
        }


        $data['user_id'] = auth('sanctum')->id();
        return Offer::create($data);
}

/**
 * accept an offer and close the project
 * @param Offer $offer
 */
public function acceptOffer(Offer $offer)
{
    return DB::transaction(function () use ($offer){

        $offer->update(['status' => 'accepted']);

        $offer->project->update(['status' => 'in_progress']);
        $freelancer = $offer->user;
        $freelancer->notify(new OfferAcceptedNotification($offer));

        dispatch(new SendOfferAcceptedEmail($offer));
       dispatch(new RejectOtherOffers($offer->project));



        return $offer;

    });
}

public function deleteOffer(Offer $offer)
{
  
   if ($offer->user_id !== auth('sanctum')->id()) {
        abort(403, 'You are not authorized to delete this offer.');
    }

    return $offer->delete();
}

public function getMyOffers()
{
    return auth('sanctum')->user()
        ->offers()
        ->with([
            'project.tags',
            'project.user',
            'project.attachments'
        ])
        ->latest()
        ->get();
}




}