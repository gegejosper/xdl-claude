<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    //
    public function index()
    {
        $roles = Role::orderBy('level', 'asc')->with('permissions')->paginate(15);
        return view('user-management.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('user-management.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255|unique:roles,name',
        'level' => 'required|integer',
        'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name, 'level' => $request->level]);

        $permissions = \Spatie\Permission\Models\Permission::whereIn('id', $request->permissions ?? [])
                        ->pluck('name')
                        ->toArray();

        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        return view('user-management.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $role->load('permissions');
        return view('user-management.roles.edit', compact('role','permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);

        // Convert permission IDs to names before syncing
        $permissions = \Spatie\Permission\Models\Permission::whereIn('id', $request->permissions ?? [])
                        ->pluck('name')
                        ->toArray();

        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success','Role deleted');
    }
}
