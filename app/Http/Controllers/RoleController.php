<?php

namespace App\Http\Controllers;

use App\Support\RolePermissionDefinitions;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * Obter todas as roles e suas permissões
     */
    public function index(): JsonResponse
    {
        $roles = RolePermissionDefinitions::all();
        
        return response()->json([
            'success' => true,
            'data' => $roles,
        ]);
    }

    /**
     * Obter uma role específica
     */
    public function show($role): JsonResponse
    {
        $roles = RolePermissionDefinitions::all();
        
        if (!isset($roles[$role])) {
            return response()->json([
                'success' => false,
                'message' => 'Role não encontrada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'name' => $role,
                'permissions' => $roles[$role],
            ],
        ]);
    }
}
