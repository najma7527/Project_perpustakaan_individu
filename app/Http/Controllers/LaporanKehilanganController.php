<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class LaporanKehilanganController extends Controller
{
        public function create()
    {
        return view('siswa.laporan-kehilangan.create');
    }

        public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required',
            'keterangan' => 'required|string'
        ]);

    Report::create([
            'user_id' => Auth:: id(),
            'book_id' => $request->book_id,
            'keterangan' => $request->keterangan,
            'status' => 'pending'
        ]);

        return redirect()
            ->route('laporan-kehilangan.index')
            ->with('success', 'Laporan kehilangan berhasil dibuat');
    }

        public function index()
    {
        $laporan = Report::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('siswa.laporan_kehilangan', compact('laporan'));
    }

        public function edit($id)
    {
        $laporan = Report::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('siswa.laporan-kehilangan.edit', compact('laporan'));
    }

        public function update(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string'
        ]);

        $laporan = Report::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($laporan->status !== 'pending') {
            return back()->with('error', 'Laporan tidak bisa diubah');
        }

        $laporan->update([
            'keterangan' => $request->keterangan
        ]);

        return redirect()
            ->route('laporan-kehilangan.index')
            ->with('success', 'Laporan berhasil diperbarui');
    }





}
