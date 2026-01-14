<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() 
    {
        // only admin can list users
        if (Auth::user()?->role !== 'admin') abort(403);
        $users = User::where('role','anggota')->get();
        return response()->json(['data' => $users]);
    }

    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        return response()->json(['ok' => true]);
    }

    public function store(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'kelas' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'kelas' => $data['kelas'] ?? null,
            'role' => $data['role'],
        ]);

        return response()->json(['message' => 'User created', 'data' => $user], 201);
    }

    public function edit(User $user)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        if ($user->role !== 'anggota') abort(403, 'Hanya dapat mengatur user dengan role anggota');
        return response()->json(['data' => $user]);
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        if ($user->role !== 'anggota') abort(403, 'Hanya dapat mengatur user dengan role anggota');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'kelas' => 'nullable|string|max:255',
        ]);

        $update = [
            'name' => $data['name'],
            'username' => $data['username'],
            'kelas' => $data['kelas'] ?? null,
        ];

        if (!empty($data['password'])) $update['password'] = Hash::make($data['password']);

        $user->update($update);
        return response()->json(['message' => 'User updated', 'data' => $user]);
    }

    public function destroy(User $user)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        if ($user->role !== 'anggota') abort(403, 'Hanya dapat mengatur user dengan role anggota');
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
