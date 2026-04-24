<?php



use App\Http\Controllers\Api\Admin\FreelancerVerificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


/*
|--------------------------------------------------------------------------
| Protected Routes (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
  
  Route::get('/freelancers', [ProfileController::class, 'index']);


Route::prefix('admin')->middleware('is_admin')->group(function () {
    

    Route::post('/verify/{profile}', [FreelancerVerificationController::class, 'verify']);
});

    //  User & Profile
    Route::get('/user', fn (Request $request) => $request->user());
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
    });

    //  Shared Project Routes (Read-only)
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
   Route::get('/notifications', function () {
    return auth('sanctum')->user()->notifications()->limit(20)->get();
});


    /*
    |--------------------------------------------------------------------------
    | Role-Based Routes
    |--------------------------------------------------------------------------
    */

    // Client Only: Project Management & Accepting Offers
    Route::middleware('role.client')->group(function () {
        Route::post('/projects/{project}/offers/{offer}/accept', [OfferController::class, 'accept']);
           Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::put('/projects/{project}', [ProjectController::class, 'update']);
        Route::get('/projects/{project}/offers', [OfferController::class, 'index']);
      
        
       



    });

    // Freelancer Only: Bidding & Offer Management
    Route::middleware(['role.freelancer', 'verified.freelancer'])->group(function () {
        Route::post('/projects/{project}/offers', [OfferController::class, 'store']);
           Route::get('/my-offers', [OfferController::class, 'myOffers']);
        Route::delete('/offers/{offer}', [OfferController::class, 'destroy']);
    });
});