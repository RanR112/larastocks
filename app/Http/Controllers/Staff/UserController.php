<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Roles;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        if (!Auth::check()) {
            abort(404, 'Page not found.');
        }

        $role = Roles::where('name', 'staff')->first();
        if (Auth::user()->role_id != $role->id) {
            abort(404, 'Page not found.');
        }
    }

    public function index(Request $request)
    {
        $query = User::with(['role', 'supplier']);
        
        // Filter by supplier if selected
        if ($request->has('supplier_id') && $request->supplier_id != '') {
            $query->where('supplier_id', $request->supplier_id);
        }

        $users = $query->paginate(10);
        $roles = Roles::all();
        $suppliers = Supplier::all();
        $title = 'User Management';
        $supplierRoleId = Roles::where('name', 'supplier')->first()->id;

        return view('staff.crud.user', compact(
            'users', 
            'roles', 
            'suppliers', 
            'title',
            'supplierRoleId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'nik' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'supplier_id' => 'nullable|exists:supplier,id'
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'nik' => $request->nik,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'supplier_id' => $request->supplier_id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create user'
            ], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nik' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id',
            'supplier_id' => 'nullable|exists:supplier,id'
        ]);

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'nik' => $request->nik,
                'role_id' => $request->role_id,
                'supplier_id' => $request->supplier_id
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update user'
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            if ($user->id === Auth::id()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You cannot delete your own account'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete user'
            ], 500);
        }
    }
}
