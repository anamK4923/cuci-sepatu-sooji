<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = $this->getUserStatistics();

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for creating a new user (admin only)
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created admin user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin',
            'no_hp' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user berhasil ditambahkan.');
    }

    /**
     * Show the form for editing admin user (admin only)
     */
    public function edit(User $user)
    {
        // Only allow editing admin users
        if ($user->role !== 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat mengedit data member.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update admin user (admin only)
     */
    public function update(Request $request, User $user)
    {
        // Only allow updating admin users
        if ($user->role !== 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak dapat mengupdate data member.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data admin berhasil diupdate.');
    }

    /**
     * Remove admin user (admin only)
     */
    public function destroy(User $user)
    {
        // Only allow deleting admin users (not members)
        if ($user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus data member.'
            ], 403);
        }

        // Prevent deleting current user
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri.'
            ], 403);
        }

        try {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin user berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus user.'
            ], 500);
        }
    }

    /**
     * Get user statistics
     */
    private function getUserStatistics()
    {
        return [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_members' => User::where('role', 'member')->count(),
            'recent_registrations' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /**
     * Export users data
     */
    public function export(Request $request)
    {
        // This would implement Excel/CSV export
        // For now, return a placeholder response
        return response()->json([
            'message' => 'Export functionality will be implemented'
        ]);
    }
}
