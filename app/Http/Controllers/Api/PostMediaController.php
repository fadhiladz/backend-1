<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostMediaRequest;
use App\Http\Resources\PostMediaResource;
use App\Models\Post;
use App\Models\PostMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostMediaController extends Controller
{
    public function store(StorePostMediaRequest $request, Post $post): PostMediaResource
    {
        abort_if($post->user_id !== $request->user()->id, 403);
        $media = $post->media()->create($request->validated());
        return new PostMediaResource($media);
    }

    public function destroy(Request $request, Post $post, PostMedia $media): JsonResponse
    {
        abort_if($post->user_id !== $request->user()->id, 403);
        $media->delete();
        return response()->json(['message' => 'Media removed.']);
    }
}
