<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserFollowController extends Controller
{
    public function followers(User $user): AnonymousResourceCollection
    {
        return UserResource::collection($user->followers()->with('profile')->paginate(20));
    }

    public function following(User $user): AnonymousResourceCollection
    {
        return UserResource::collection($user->following()->with('profile')->paginate(20));
    }

    public function follow(Request $request, User $user): JsonResponse
    {
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'You cannot follow yourself.'], 422);
        }
        $request->user()->following()->syncWithoutDetaching([$user->id]);
        return response()->json(['message' => 'Followed.']);
    }

    public function unfollow(Request $request, User $user): JsonResponse
    {
        $request->user()->following()->detach($user->id);
        return response()->json(['message' => 'Unfollowed.']);
    }
}
