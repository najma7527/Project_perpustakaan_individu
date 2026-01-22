<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
    ]);

    $credentials = [
        'username' => $request->username,
        'password' => $request->password,
        'status'   => 'aktif', // hanya izinkan login jika status aktif
    ];

    try {
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $intended = Auth::user()->role === 'admin'
                ? route('admin.dashboard')
                : route('anggota.dashboard');

            return redirect()->intended($intended);
        }

    } catch (\RuntimeException $e) {
        logger()->error('Login hash error: ' . $e->getMessage());

        return back()->withErrors([
            'username' => 'Username salah, atau akun belum diaktifkan.',
            'password' => 'Password akun ini tidak valid. Hubungi admin untuk mengetahui password anda.',
        ]);
    }
}


    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'nis/nisn' => 'nullable|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'kelas' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'nin/nisn' => $data['nis/nisn'] ?? null,
            'password' => Hash::make($data['password']),
            'kelas' => $data['kelas'] ?? null,
            'role' => 'anggota',
            'status' => 'nonaktif',
        ]);

        Auth::login($user);
        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
