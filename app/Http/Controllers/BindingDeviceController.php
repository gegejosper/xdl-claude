<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Cache;
class BindingDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('devices')
        ->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'superadmin');
        })
        ->orderBy('name', 'ASC')
        ->paginate(10);

        return view('binding_devices.index', compact('users'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $bindingDevice)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, UserDevice $bindingDevice)
    {
        // Reset the device ID
        $bindingDevice->delete();
        Cache::forget('user_active_device_' . $bindingDevice->user_id);
        return back()->with('success', 'Device ID removed successfully.'); 
        
        // If the user resetting is the logged-in user, log them out
        //if (auth()->id() === $bindingDevice->user_id) {
            //Auth::logout();
            //$request->session()->invalidate();
            //$request->session()->regenerateToken();
            //Cache::forget('user_active_device_' . $bindingDevice->user_id);

            //return redirect('/login')->with('error', 'Your device has been reset. Please log in again.');
         //}
        
         
    }
    
}
