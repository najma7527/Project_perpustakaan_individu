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
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $credentials = [
            'username' => $validated['username'],
            'password' => $validated['password'],
            'status'   => 'aktif',
        ];

        $remember = $validated['remember'] ?? false;

        try {
            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();

                $intended = Auth::user()->role === 'admin'
                    ? route('admin.dashboard')
                    : route('anggota.dashboard');

                return redirect()->intended($intended);
            }

            return back()->withErrors([
                'username' => 'Username atau password salah, atau akun belum diaktifkan.',
            ])->onlyInput('username');

        } catch (\RuntimeException $e) {
            logger()->error('Login hash error: ' . $e->getMessage());

            return back()->withErrors([
                'username' => 'Terjadi kesalahan saat login. Silahkan hubungi admin.',
            ])->onlyInput('username');
        }
    }


    public function showRegisterAdmin()
    {
        return view('auth.register-admin');
    }

    public function registerAdmin(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'telephone' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'remember' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'telephone' => $data['telephone'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        $remember = $data['remember'] ?? false;
        Auth::login($user, $remember);
        return redirect()->route('login')->with('success', 'Registrasi admin berhasil!');
    }

    public function showRegisterAnggota()
    {
        return view('auth.register-anggota');
    }

    public function registerAnggota(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'nis_nisn' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
            'kelas' => 'nullable|string|max:255',
            'remember' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'nis_nisn' => $data['nis_nisn'],
            'telephone' => $data['telephone'],
            'password' => Hash::make($data['password']),
            'kelas' => $data['kelas'],
            'role' => 'anggota',
            'status' => 'menunggu',
        ]);

        $remember = $data['remember'] ?? false;
        Auth::login($user, $remember);
        return View('auth.succes_register');    
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
