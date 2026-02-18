<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users (Admin only).
     */
    public function index()
    {
        $this->authorizeAdmin();
        $users = User::with(['role', 'department'])->latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $this->authorizeAdmin();
        $roles = Role::all();
        $departments = Department::all();
        return view('users.create', compact('roles', 'departments'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'role_id'       => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'phone'         => 'nullable|string|max:20',
        ]);

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role_id'       => $request->role_id,
            'department_id' => $request->department_id,
            'phone'         => $request->phone,
            'is_active'     => true,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display user detail.
     */
    public function show(string $id)
    {
        $this->authorizeAdmin();
        $user = User::with(['role', 'department', 'attendances.event', 'approvalRequests'])->findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing a user.
     */
    public function edit(string $id)
    {
        $this->authorizeAdmin();
        $user = User::findOrFail($id);
        $roles = Role::all();
        $departments = Department::all();
        return view('users.edit', compact('user', 'roles', 'departments'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, string $id)
    {
        $this->authorizeAdmin();
        $user = User::findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'role_id'       => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'phone'         => 'nullable|string|max:20',
            'is_active'     => 'boolean',
            'password'      => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name'          => $request->name,
            'email'         => $request->email,
            'role_id'       => $request->role_id,
            'department_id' => $request->department_id,
            'phone'         => $request->phone,
            'is_active'     => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate.');
    }

    /**
     * Deactivate (soft delete) a user.
     */
    public function destroy(string $id)
    {
        $this->authorizeAdmin();
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => false]);

        return redirect()->route('users.index')->with('success', 'User berhasil dinonaktifkan.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(string $id)
    {
        $this->authorizeAdmin();
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User berhasil {$status}.");
    }

    private function authorizeAdmin()
    {
        if (!auth()->user()->canManageUsers()) {
            abort(403, 'Akses ditolak. Hanya Admin IT yang dapat mengelola akun.');
        }
    }
}
