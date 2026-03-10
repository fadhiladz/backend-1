<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\UpdateGroupMemberRequest;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupMemberController extends Controller
{
    public function index(Group $group): AnonymousResourceCollection
    {
        $members = $group->members()->with('profile')->paginate(20);
        return UserResource::collection($members);
    }

    public function join(Request $request, Group $group): JsonResponse
    {
        if ($group->members()->where('user_id', $request->user()->id)->exists()) {
            return response()->json(['message' => 'Already a member.'], 422);
        }
        $group->members()->attach($request->user()->id, ['role' => 'member', 'status' => 'active']);
        return response()->json(['message' => 'Joined group.']);
    }

    public function leave(Request $request, Group $group): JsonResponse
    {
        abort_if($group->owner_id === $request->user()->id, 422, 'Owner cannot leave the group.');
        $group->members()->detach($request->user()->id);
        return response()->json(['message' => 'Left group.']);
    }

    public function update(UpdateGroupMemberRequest $request, Group $group, User $user): JsonResponse
    {
        abort_if($group->owner_id !== $request->user()->id, 403);
        $group->members()->updateExistingPivot($user->id, $request->validated());
        return response()->json(['message' => 'Member updated.']);
    }

    public function destroy(Request $request, Group $group, User $user): JsonResponse
    {
        abort_if($group->owner_id !== $request->user()->id, 403);
        $group->members()->detach($user->id);
        return response()->json(['message' => 'Member removed.']);
    }
}
