<?php 
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException ;


class AuthService
{
    /**
     * register new user
     * @param array $data
     * @return array{token: string, user: User}
     */
    public function registerUser(array $data): array
    {
        $user = User::create([
            'first_name' =>$data['first_name'],
            'last_name' =>$data['last_name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
            'city_id' => $data['city_id'] ?? 1,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
    /**
     * login 
     * @param array $credentials
     * @return array{token: string, user: User|\stdClass}
     */
    public function loginUser(array $credentials): array
    {
        $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }
        $token=  $user->createToken('auth_token')->plainTextToken;

        return [
        'token' => $token,
        'user'  => $user,
    ];
    }
}

