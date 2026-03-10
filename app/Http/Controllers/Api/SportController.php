<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sport\StoreSportRequest;
use App\Http\Resources\SportResource;
use App\Models\Sport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SportController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return SportResource::collection(Sport::all());
    }

    public function store(StoreSportRequest $request): SportResource
    {
        $sport = Sport::create($request->validated());
        return new SportResource($sport);
    }

    public function destroy(Sport $sport): JsonResponse
    {
        $sport->delete();
        return response()->json(['message' => 'Sport deleted.']);
    }
}
