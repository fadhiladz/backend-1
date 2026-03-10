<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Permission::all());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name'],
        ]);

        $permission = Permission::create(['name' => $request->name, 'guard_name' => 'web']);

        return response()->json($permission, 201);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully.']);
    }
}
