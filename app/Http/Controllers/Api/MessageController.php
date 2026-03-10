<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MessageController extends Controller
{
    public function index(Request $request, Conversation $conversation): AnonymousResourceCollection
    {
        abort_if(
            !$conversation->participants()->where('user_id', $request->user()->id)->exists(),
            403
        );
        $messages = $conversation->messages()->with('sender.profile')->paginate(30);
        return MessageResource::collection($messages);
    }

    public function store(StoreMessageRequest $request, Conversation $conversation): MessageResource
    {
        abort_if(
            !$conversation->participants()->where('user_id', $request->user()->id)->exists(),
            403
        );
        $message = $conversation->messages()->create(array_merge(
            $request->validated(),
            ['sender_id' => $request->user()->id]
        ));
        return new MessageResource($message->load('sender.profile'));
    }

    public function destroy(Request $request, Message $message): JsonResponse
    {
        abort_if($message->sender_id !== $request->user()->id, 403);
        $message->delete();
        return response()->json(['message' => 'Message deleted.']);
    }
}
