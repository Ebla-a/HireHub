<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class OfferController extends Controller
{
    use AuthorizesRequests;
    protected $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }
    /**
     * Presenate an offer (freelancer only)
     * @param StoreOfferRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreOfferRequest $request)
    {
        $offer = $this->offerService->storeOffer($request->validated());
        return response()->json([
            'message' => 'your offer created successfully',
            'data' => new OfferResource($offer->load('user')),
        ],201);

    }
    /**
     * accept offer (client)
     * @param Offer $offer
     * @return \Illuminate\Http\JsonResponse
     */
    public function accept(Offer $offer)
    {
        $this->authorize('acceptOffer', $offer->project);

    $this->offerService->acceptOffer($offer);
    
    return response()->json([
        'message' => 'The offer was accepted and the project is now in progress'
    ]);

    }
}
