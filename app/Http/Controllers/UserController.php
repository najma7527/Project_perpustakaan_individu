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
            'nis/nisn' => 'nullable|string|max:255',    
            'password' => 'required|string|min:6|confirmed',
            'kelas' => 'nullable|string|max:255',
            'role' => 'required|in:anggota,admin',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'nin/nisn' => $data['nis/nisn'] ?? null,
            'password' => Hash::make($data['password']),
            'kelas' => $data['kelas'] ?? null,
            'role' => $data['role'],
            'status' => 'aktif',
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
            'nis/nisn' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'kelas' => 'nullable|string|max:255',
        ]);

        $update = [
            'name' => $data['name'],
            'username' => $data['username'],
            'kelas' => $data['kelas'] ?? null,
            'nin/nisn' => $data['nis/nisn'] ?? null,
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

    public function approve($id)
{
    if (Auth::user()?->role !== 'admin') abort(403);

    $user = User::findOrFail($id);

    $user->update([
        'status' => 'aktif'
    ]);

    return response()->json([
        'message' => 'Akun berhasil di-approve dan diaktifkan.',
        'data' => $user
    ]);
}


    public function search(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $query = $request->query('q');
        $users = User::where('role', 'anggota')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('username', 'like', "%$query%")
                  ->orWhere('nis/nisn', 'like', "%$query%");
            })
            ->get();
        return response()->json(['data' => $users]);
    }
}
