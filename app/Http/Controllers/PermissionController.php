<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class PermissionController extends Controller
{
    //
    public function index()
    {
        $permissions = Permission::paginate(20);
        return view('user-management.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('user-management.permissions.create');
    }

    public function store(Request $request)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|unique:permissions,name',
        // ]);

        // Permission::create(['name' => $data['name']]);
        // return redirect()->route('permissions.index')->with('success', 'Permission created');
        $data = $request->validate([
        'name' => 'required|string',
        ]);

        // Split by comma, trim whitespace, remove duplicates & empties
        $names = collect(explode(',', $data['name']))
            ->map(fn($name) => trim($name))
            ->filter()
            ->unique();

        $created = [];

        foreach ($names as $name) {
            // Avoid duplicates in DB
            if (!Permission::where('name', $name)->exists()) {
                $created[] = Permission::create(['name' => $name]);
            }
        }

        return redirect()
            ->route('permissions.index')
            ->with('success', count($created) . ' permission(s) created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('user-management.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:permissions,name,'.$permission->id,
        ]);
        $permission->name = $data['name'];
        $permission->save();
        return redirect()->route('permissions.index')->with('success','Permission updated');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('user-management.index')->with('success','Permission deleted');
    }
}
