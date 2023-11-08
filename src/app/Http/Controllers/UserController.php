<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\UserSettingsUpdateRequest;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * @group User management
 *
 * Endpoints for user management.
 */
class UserController extends Controller
{
    /**
     * Get user settings
     */
    public function settings(): JsonResponse
    {
        return response()->json(auth()->user()->settings);
    }

    /**
     * Update user settings
     */
    public function updateSettings(UserSettingsUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();

        auth()->user()->settings->update($data);

        return response()->json(auth()->user()->settings);
    }
}
