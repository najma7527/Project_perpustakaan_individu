<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // TAMPIL HALAMAN PROFIL
    public function show()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    // UPDATE DATA PROFIL
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403);
            }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'telephone' => 'nullable|string|max:255',
            'nis_nisn' => 'nullable|string|max:255',
            'kelas' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->telephone = $request->telephone;
        $user->nis_nisn = $request->nis_nisn;
        $user->kelas = $request->kelas;

        // Upload foto
        if ($request->hasFile('profile_photo')) {

            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')
                            ->store('profile_photos', 'public');

            $user->profile_photo = $path;
        }

        $user->save();

        return redirect()
            ->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui');
    }

    // HAPUS FOTO PROFIL
    public function deletePhoto()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->profile_photo = null;
        $user->save();

        return redirect()
            ->back()
            ->with('success', 'Foto profil berhasil dihapus');
    }
}
