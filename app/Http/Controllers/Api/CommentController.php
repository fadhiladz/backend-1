<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentController extends Controller
{
    public function index(Post $post): AnonymousResourceCollection
    {
        $comments = $post->comments()->with('user.profile', 'replies.user.profile')->paginate(20);
        return CommentResource::collection($comments);
    }

    public function store(StoreCommentRequest $request, Post $post): CommentResource
    {
        $comment = $post->comments()->create([
            'user_id'   => $request->user()->id,
            'content'   => $request->content,
            'parent_id' => $request->parent_id ?? null,
        ]);
        return new CommentResource($comment->load('user.profile'));
    }

    public function update(UpdateCommentRequest $request, Comment $comment): CommentResource
    {
        abort_if($comment->user_id !== $request->user()->id, 403);
        $comment->update($request->validated());
        return new CommentResource($comment->load('user.profile'));
    }

    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        abort_if($comment->user_id !== $request->user()->id, 403);
        $comment->delete();
        return response()->json(['message' => 'Comment deleted.']);
    }
}
