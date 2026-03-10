<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\StoreGroupRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $groups = Group::with('owner.profile', 'sport')->withCount('members')->paginate(15);
        return GroupResource::collection($groups);
    }

    public function store(StoreGroupRequest $request): GroupResource
    {
        $group = Group::create(array_merge($request->validated(), ['owner_id' => $request->user()->id]));

        // Auto-add creator as admin member
        $group->members()->attach($request->user()->id, ['role' => 'admin', 'status' => 'active']);

        return new GroupResource($group->load('owner.profile', 'sport')->loadCount('members'));
    }

    public function show(Group $group): GroupResource
    {
        return new GroupResource($group->load('owner.profile', 'sport')->loadCount('members'));
    }

    public function update(UpdateGroupRequest $request, Group $group): GroupResource
    {
        abort_if($group->owner_id !== $request->user()->id, 403);
        $group->update($request->validated());
        return new GroupResource($group->load('owner.profile', 'sport')->loadCount('members'));
    }

    public function destroy(Request $request, Group $group): JsonResponse
    {
        abort_if($group->owner_id !== $request->user()->id, 403);
        $group->delete();
        return response()->json(['message' => 'Group deleted.']);
    }
}
