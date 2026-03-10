<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Conversation\StoreConversationRequest;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ConversationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $conversations = $request->user()
            ->conversations()
            ->with('participants.profile', 'latestMessage.sender')
            ->latest('created_at')
            ->paginate(20);
        return ConversationResource::collection($conversations);
    }

    public function store(StoreConversationRequest $request): ConversationResource
    {
        $participantIds = array_unique(
            array_merge($request->participant_ids, [$request->user()->id])
        );

        // For direct chats, reuse existing conversation
        if ($request->type === 'direct' && count($participantIds) === 2) {
            $existing = Conversation::where('type', 'direct')
                ->whereHas('participants', fn($q) => $q->where('user_id', $participantIds[0]))
                ->whereHas('participants', fn($q) => $q->where('user_id', $participantIds[1]))
                ->first();

            if ($existing) {
                return new ConversationResource($existing->load('participants.profile'));
            }
        }

        $conversation = Conversation::create([
            'type' => $request->type,
            'name' => $request->name,
        ]);

        foreach ($participantIds as $userId) {
            $conversation->participants()->attach($userId);
        }

        return new ConversationResource($conversation->load('participants.profile'));
    }
}
