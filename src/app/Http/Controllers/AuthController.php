<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group User authentication
 */
class AuthController extends Controller
{
    /**
     * Log in via email and password, and get a personal access token.
     * @param LoginRequest $request
     * @return JsonResponse
     * @unauthenticated
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!auth()->validate($credentials)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        /** @var $user User */
        $user = User::where('email', $credentials['email'])->first();

        return response()->json([
            'token' => $user->createToken(time())->plainTextToken,
        ]);
    }

    /**
     * Return the currently logged in user resource.
     * @return \App\Http\Resources\UserResource
     */
    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log out.
     */
    public function logout(): void
    {
        auth()->user()->currentAccessToken()->delete();

        response()->noContent();
    }
}
