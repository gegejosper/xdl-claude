<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\BranchUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    // ─── List ─────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $branches = Branch::withCount('branch_users')
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('branch_name', 'like', "%{$request->search}%")
                  ->orWhere('branch_code', 'like', "%{$request->search}%");
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $page_name = 'Branches';
        return view('branches.index', compact('branches', 'page_name'));
    }

    // ─── Store (Create) ───────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_name'    => 'required|string|max:100',
            'branch_code'    => 'required|string|max:20|unique:branches,branch_code',
            'type'           => 'required|in:main,sub,kiosk',
            'address'        => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'status'         => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $branch = Branch::create($request->only(
                'branch_name', 'branch_code', 'type', 'address', 'contact_number', 'status'
            ));

            DB::commit();
            Log::info(Auth::user()->name . " created branch: {$branch->branch_name}");

            return response()->json([
                'success' => true,
                'message' => 'Branch created.',
                'branch'  => $branch,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Branch store failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to create branch.']], 500);
        }
    }

    // ─── Show (Detail + Users) ────────────────────────────────────────────────

    public function show(int $id)
    {
        $branch = Branch::with(['branch_users.user'])->findOrFail($id);

        // Users NOT assigned to this branch, for the add-user dropdown
        $assigned_user_ids = $branch->branch_users->pluck('user_id');
        $available_users   = User::whereNotIn('id', $assigned_user_ids)
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $page_name = $branch->branch_name;
        return view('branches.show', compact('branch', 'available_users', 'page_name'));
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function update(Request $request, int $id)
    {
        $branch = Branch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'branch_name'    => 'required|string|max:100',
            'branch_code'    => "required|string|max:20|unique:branches,branch_code,{$id}",
            'type'           => 'required|in:main,sub,kiosk',
            'address'        => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'status'         => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $branch->update($request->only(
                'branch_name', 'branch_code', 'type', 'address', 'contact_number', 'status'
            ));

            DB::commit();
            Log::info(Auth::user()->name . " updated branch: {$branch->branch_name}");

            return response()->json(['success' => true, 'message' => 'Branch updated.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Branch update failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to update branch.']], 500);
        }
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────

    public function destroy(int $id)
    {
        $branch = Branch::findOrFail($id);

        if ($branch->branch_users()->count() > 0) {
            return response()->json([
                'errors' => ['general' => 'Cannot delete a branch that still has assigned users. Remove all users first.'],
            ], 422);
        }

        $branch->delete();
        Log::info(Auth::user()->name . " deleted branch: {$branch->branch_name}");

        return response()->json(['success' => true, 'message' => 'Branch deleted.']);
    }

    // ─── Add User to Branch ───────────────────────────────────────────────────

    public function add_user(Request $request, int $id)
    {
        $branch = Branch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if user is already assigned to ANY branch
        $existing = BranchUser::where('user_id', $request->user_id)->first();
        if ($existing) {
            $other_branch = Branch::find($existing->branch_id);
            return response()->json([
                'errors' => ['user_id' => ["This user is already assigned to branch: {$other_branch?->branch_name}. Remove them first."]],
            ], 422);
        }

        DB::beginTransaction();
        try {
            BranchUser::create([
                'branch_id' => $branch->id,
                'user_id'   => $request->user_id,
            ]);

            $user = User::find($request->user_id);
            DB::commit();
            Log::info(Auth::user()->name . " assigned user {$user->name} to branch {$branch->branch_name}");

            return response()->json([
                'success' => true,
                'message' => "{$user->name} assigned to {$branch->branch_name}.",
                'user'    => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->primary_role_name(),
                ],
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Branch add_user failed: ' . $e->getMessage());
            return response()->json(['errors' => ['general' => 'Failed to assign user.']], 500);
        }
    }

    // ─── Remove User from Branch ──────────────────────────────────────────────

    public function remove_user(Request $request, int $id)
    {
        $branch = Branch::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $branch_user = BranchUser::where('branch_id', $branch->id)
            ->where('user_id', $request->user_id)
            ->first();

        if (!$branch_user) {
            return response()->json(['errors' => ['general' => 'User is not assigned to this branch.']], 422);
        }

        $user = User::find($request->user_id);
        $branch_user->delete();

        Log::info(Auth::user()->name . " removed user {$user->name} from branch {$branch->branch_name}");

        return response()->json([
            'success' => true,
            'message' => "{$user->name} removed from {$branch->branch_name}.",
        ]);
    }
}
