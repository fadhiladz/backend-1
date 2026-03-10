<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FriendRequest\UpdateFriendRequestRequest;
use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FriendRequestController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $requests = FriendRequest::where('receiver_id', $request->user()->id)
            ->where('status', 'pending')
            ->with('sender.profile')
            ->paginate(20);
        return FriendRequestResource::collection($requests);
    }

    public function store(Request $request, User $user): JsonResponse
    {
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'You cannot send a friend request to yourself.'], 422);
        }

        $existing = FriendRequest::where('sender_id', $request->user()->id)
            ->where('receiver_id', $user->id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Friend request already sent.'], 422);
        }

        $friendRequest = FriendRequest::create([
            'sender_id'   => $request->user()->id,
            'receiver_id' => $user->id,
        ]);

        return response()->json(new FriendRequestResource($friendRequest->load('sender', 'receiver')), 201);
    }

    public function update(UpdateFriendRequestRequest $request, FriendRequest $friendRequest): FriendRequestResource
    {
        abort_if($friendRequest->receiver_id !== $request->user()->id, 403);
        $friendRequest->update($request->validated());
        return new FriendRequestResource($friendRequest->load('sender', 'receiver'));
    }

    public function destroy(Request $request, FriendRequest $friendRequest): JsonResponse
    {
        abort_if($friendRequest->sender_id !== $request->user()->id, 403);
        $friendRequest->delete();
        return response()->json(['message' => 'Friend request cancelled.']);
    }
}
