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

        try {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $intended = Auth::user()->role === 'admin' ? route('transactions.index') : route('books.index');
                return redirect()->intended($intended);
            }

            return back()->withErrors([
                'username' => 'Mohon masukan lagi username anda',
                'password' => 'Mohon masukan lagi password anda',
            ]);
        } catch (\RuntimeException $e) {
            // Likely an incompatible password hash stored in DB (not bcrypt)
            // Return friendly error and log exception
            logger()->error('Login hash error: ' . $e->getMessage());
            return back()->withErrors([
                'password' => 'Stored password for this account is invalid. Reset administrator password or re-seed the admin account.',
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
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'nin/nisn' => $data['nis/nisn'] ?? null,
            'password' => Hash::make($data['password']),
            'kelas' => $data['kelas'] ?? null,
            'role' => 'anggota',
        ]);

        Auth::login($user);
        return redirect('/books');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
