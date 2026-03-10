<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfile\UpdateUserProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show(User $user): UserProfileResource
    {
        $profile = $user->profile ?? $user->profile()->create([]);
        return new UserProfileResource($profile);
    }

    public function update(UpdateUserProfileRequest $request): UserProfileResource
    {
        $user = $request->user();
        $profile = $user->profile ?? $user->profile()->create([]);
        $profile->update($request->validated());
        return new UserProfileResource($profile);
    }
}
