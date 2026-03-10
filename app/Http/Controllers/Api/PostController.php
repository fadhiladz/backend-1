<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $posts = Post::with(['user.profile', 'media'])
            ->withCount(['likes', 'comments'])
            ->where('visibility', 'public')
            ->latest()
            ->paginate(15);
        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request): PostResource
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $post = Post::create([
            'user_id'       => $data['user_id'],
            'postable_type' => $data['postable_type'],
            'postable_id'   => $data['postable_id'],
            'content'       => $data['content'] ?? null,
            'visibility'    => $data['visibility'] ?? 'public',
        ]);

        if (!empty($data['media'])) {
            foreach ($data['media'] as $index => $item) {
                $post->media()->create([
                    'media_url'  => $item['url'],
                    'media_type' => $item['type'],
                    'sort_order' => $index,
                ]);
            }
        }

        return new PostResource($post->load('user.profile', 'media')->loadCount(['likes', 'comments']));
    }

    public function show(Post $post): PostResource
    {
        return new PostResource($post->load('user.profile', 'media', 'comments.user.profile')->loadCount(['likes', 'comments']));
    }

    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        abort_if($post->user_id !== $request->user()->id, 403);
        $post->update($request->validated());
        return new PostResource($post->load('user.profile', 'media')->loadCount(['likes', 'comments']));
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        abort_if($post->user_id !== $request->user()->id, 403);
        $post->delete();
        return response()->json(['message' => 'Post deleted.']);
    }
}
