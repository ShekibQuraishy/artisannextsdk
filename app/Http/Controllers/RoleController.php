<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'totalUsers' => $role->users()->count(),
                'permissions' => $role->permissions->pluck('name'),
                'createdAt' => $role->created_at,
            ];
        });

        return response()->json(['data' => $roles]);
    }

    public function store(RoleRequest $request)
    {
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return response()->json([
            'message' => 'Role created successfully',
            'data' => $role->load('permissions')
        ], 201);
    }

    public function getAllPermissions()
    {
        $permissions = Permission::all()->map(function ($permission) {
            return [
                'value' => $permission->name,
                'label' => ucwords(str_replace('.', ' ', $permission->name))
            ];
        });

        return response()->json(['data' => $permissions]);
    }
} 