<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    //
    public function index()
    {
        $roles = Role::all();
        $users = User::with('roles','permissions', 'devices')->paginate(20);
        return view('user-management.users.lists', compact('users', 'roles'));
    }

    public function create()
    {
        // $roles = Role::all();
        $permissions = Permission::all();
        $auth_user = Auth::user();
        $auth_level = $auth_user->role_level();
        //dd($auth_level);
        // show only roles LOWER than the current user's level
        $roles = Role::where('level', '>', $auth_level)->get();
        return view('user-management.users.create', compact('roles','permissions'));
    }

    public function store(Request $request)
    {
       $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'username' => 'required|unique:users,username',
        'password' => 'required|string|min:6|confirmed',
        'role_id' => 'required|exists:roles,id',
        'permission_ids' => 'array',
        'permission_ids.*' => 'exists:permissions,id',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'status' => 'active',
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // assign single role
        $role = Role::findOrFail($data['role_id']);
        $user->syncRoles([$role->name]);

        if (!empty($data['permission_ids'])) {
            $permission_names = Permission::whereIn('id', $data['permission_ids'])->pluck('name')->toArray();
            $user->syncPermissions($permission_names);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $user->load('roles','permissions');
        return view('user-management.users.edit', compact('user','roles','permissions'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:6|confirmed',
        'role_id' => 'required|exists:roles,id',
        'permission_ids' => 'array',
        'permission_ids.*' => 'exists:permissions,id',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->status = $request->input('status', $user->status);
        $user->save();

        $role = Role::findOrFail($data['role_id']);
        $user->syncRoles([$role->name]);

        $permission_names = Permission::whereIn('id', $data['permission_ids'] ?? [])->pluck('name')->toArray();
        $user->syncPermissions($permission_names);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success','User deleted');
    }
    public function unbinding(Request $request, UserDevice $unbind)
    {
        // Reset the device ID
        $unbind->delete();
        Cache::forget('user_active_device_' . $unbind->user_id);
        return redirect()->route('users.index')->with('success', 'Unbind Successfully');
        
    }

    public function restricted(Request $request, User $restrict)
    {
        $restriction = $restrict->restriction == 'yes' ? 'no' : 'yes';
        $restrict->update([
            'restriction' => $restriction
        ]);
        return redirect()->route('users.index');
        
    }
}
