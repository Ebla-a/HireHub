<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginReuest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * register new user
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
   
        $result = $this->authService->registerUser($request->validated());

        return response()->json([
            'status'  => 'success',
            'message' => 'User registered successfully',
            'data'    => $result
        ], 201);
    }

    /**
     * login
     * @param loginReuest $request
     * @return JsonResponse
     */
    public function login(loginReuest $request): JsonResponse
    {
    
       $result = $this->authService->loginUser($request->validated());

    return response()->json([
        'status'  => 'success',
        'message' => 'Login successful',
        'token'   => $result['token'],
        'user'    => $result['user']
    ]);
    }
}

