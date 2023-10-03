<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!auth()->validate($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        /** @var User */
        $user = User::where('email', $credentials['email'])->first();

        return response()->json([
            'token' => $user->createToken(time())->plainTextToken,
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
    }
}
