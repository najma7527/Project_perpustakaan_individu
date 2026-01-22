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
        return view('admin.kelola-anggota.index',compact('users'));
    }

    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        return view('admin.kelola-anggota.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'telephone' => 'nullable|string|max:255',
            'nis_nisn' => 'nullable|string|max:255',    
            'password' => 'required|string|min:6|confirmed',
            'kelas' => 'nullable|string|max:255',
            'role' => 'required|in:anggota,admin',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'telephone' => $data['telephone'] ?? null,
            'nis_nisn' => $data['nis_nisn'] ?? null,
            'password' => Hash::make($data['password']),
            'kelas' => $data['kelas'] ?? null,
            'role' => $data['role'],
            'status' => 'aktif',
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        if ($user->role !== 'anggota') abort(403, 'Hanya dapat mengatur user dengan role anggota');
        return view('admin.kelola-anggota.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        if ($user->role !== 'anggota') abort(403, 'Hanya dapat mengatur user dengan role anggota');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'telephone' => 'nullable|string|max:255',
            'nis_nisn' => 'nullable|string|max:255',
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
        return redirect()->back()->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        if ($user->role !== 'anggota') abort(403, 'Hanya dapat mengatur user dengan role anggota');
        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus');
    }

    public function approve($id)
{
    if (Auth::user()?->role !== 'admin') abort(403);

    $user = User::findOrFail($id);

    $user->update([
        'status' => 'aktif'
    ]);

    return redirect()->back()->with('success', 'User berhasil diaktifkan');
}


   public function search(Request $request)
{
    if (Auth::user()->role !== 'admin') abort(403);

    $q = $request->q;

    $query = User::where('role', 'anggota');

    if ($q) {
        $query->where(function ($q2) use ($q) {
            $q2->where('name', 'like', "%$q%")
               ->orWhere('username', 'like', "%$q%")
               ->orWhere('nis_nisn', 'like', "%$q%");
        });
    }

    return response()->json([
        'data' => $query->limit(20)->get()
    ]);
}

}
