<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserSettingsUpdateRequest;

/**
 * @group User management
 *
 * Endpoints for user management.
 */
class UserController extends Controller
{
    /**
     * Update user settings
     *
     * @param UserSettingsUpdateRequest $request
     * @return JsonResponse
     */
    public function updateSettings(UserSettingsUpdateRequest $request): JsonResponse
    {
        $data = $request->validated();

        auth()->user()->settings->update($data);

        return response()->json(auth()->user()->settings);
    }
}
