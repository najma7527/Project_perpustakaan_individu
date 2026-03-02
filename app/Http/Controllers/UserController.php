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
    /**
     * Menampilkan daftar anggota dengan filter Search, Tanggal, Kelas, dan Status
     */
    public function index(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        // Ambil filter dari request
        $search = $request->get('search');
        $date = $request->get('date');
        $kelas = $request->get('kelas');
        $tab = $request->get('tab', 'verifikasi'); // Default tab ke verifikasi

        $query = User::where('role', 'anggota');

        // Logic Filter Tab
if ($tab == 'verifikasi') {
    $query->where('status', 'menunggu');

} elseif ($tab == 'diterima') {
    $query->whereIn('status', ['aktif', 'nonaktif']);

} elseif ($tab == 'ditolak') {
    $query->where('status', 'ditolak');
}


        // Search: Nama, Username, NIS/NISN atau kelas
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('nis_nisn', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        // Filter kelas (separate from search)
        if ($kelas) {
            $query->where('kelas', $kelas);
        }

        // Filter Tanggal (berdasarkan created_at)
        if ($date) {
            $query->whereDate('tanggal_pengajuan', $date);
        }


        $users = $query->latest()->paginate(10);

    return view('admin.kelola_data_anggota', compact('users', 'tab', 'search', 'kelas', 'date'
));

    }

    /**
     * Mengubah status (Aksi Centang/Diterima & Silang/Ditolak)
     */
    public function updateStatus(Request $request, $id)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $user = User::findOrFail($id);
        
        $request->validate([
        'status' => 'required|in:aktif,nonaktif,menunggu,ditolak',
        ]);

        $user->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status anggota berhasil diperbarui.');
    }

    /**
     * Lihat Detail Anggota
     */
    public function show($id)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $user = User::findOrFail($id);
        // Mengembalikan view detail (bisa dalam bentuk modal atau halaman baru)
        return view('admin.anggota.show', compact('user'));
    }

    /**
     * Update Status Anggota (Hanya status saja)
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $user = User::findOrFail($id);

        $request->validate([
            'status'   => 'required|in:aktif,nonaktif,menunggu,ditolak'
        ]);

        $user->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status anggota berhasil diperbarui.');
    }

    /**
     * Reset Password Anggota
     */
    public function resetPassword(Request $request, $id)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $user = User::findOrFail($id);

        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Password anggota berhasil direset.');
    }


    /**
     * Hapus Anggota
     */
    public function destroy($id)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->back()->with('success', 'Anggota berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus anggota: ' . $e->getMessage());
        }
    }
}
