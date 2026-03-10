<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Group;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GroupPostController extends Controller
{
    public function index(Group $group): AnonymousResourceCollection
    {
        $posts = $group->posts()
            ->with('user.profile', 'media')
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(15);
        return PostResource::collection($posts);
    }
}
