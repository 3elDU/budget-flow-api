<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
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
     * Return the currently logged in user resource.
     * @return UserResource
     * @apiResource App\Http\Resources\UserResource
     * @apiResourceModel App\Models\User
     */
    public static function me(): UserResource
    {
        return new UserResource(auth()->user());
    }

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
