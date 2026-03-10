<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SportResource;
use App\Models\Sport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserSportController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return SportResource::collection($request->user()->sports);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['sport_id' => 'required|exists:sports,id']);
        $request->user()->sports()->syncWithoutDetaching([$request->sport_id]);
        return response()->json(['message' => 'Sport added.']);
    }

    public function destroy(Request $request, Sport $sport): JsonResponse
    {
        $request->user()->sports()->detach($sport->id);
        return response()->json(['message' => 'Sport removed.']);
    }
}
