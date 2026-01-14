<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\transaction;
use App\Models\books;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()?->role === 'admin') {
            $transactions = transaction::with(['book','user'])->get();
        } else {
            $transactions = transaction::with(['book','user'])->where('user_id', Auth::id())->get();
        }

        return response()->json(['data' => $transactions]);
    }

    public function create()
    {
        $books = books::where('stok','>',0)->get();
        return response()->json(['books' => $books]);
    }

    public function store(Request $request)
    {
            $rules = [
            'buku_id' => 'required|exists:books,id',
            'tanggal_pinjam' => 'required|date',
        ];
        if (Auth::user()?->role === 'admin') {
            $rules['user_id'] = 'required|exists:users,id';
        }

        $data = $request->validate($rules);

        if (Auth::user()?->role !== 'admin') {
            $data['user_id'] = Auth::id();
        }

        return DB::transaction(function() use ($data) {
            $book = books::lockForUpdate()->find($data['buku_id']);
            if (!$book || $book->stok <= 0) {
                return response()->json(['error' => 'Buku tidak tersedia'], 422);
            }

            $trans = transaction::create([
                'user_id' => $data['user_id'],
                'buku_id' => $data['buku_id'],
                'tanggal_pinjam' => $data['tanggal_pinjam'],
                'tanggal_kembali' => null,
                'status' => 'dipinjam',
            ]);

            $book->decrement('stok', 1);
            return response()->json(['message' => 'Transaction created', 'data' => $trans], 201);
        });
    }

    public function show(transaction $transaction)
    {
        if (Auth::user()?->role !== 'admin' && $transaction->user_id !== Auth::id()) abort(403);

        $transaction->load(['book','user']);
        return response()->json(['data' => $transaction]);
    }

    public function returnBook(Request $request, transaction $transaction)
    {
        if (Auth::user()?->role !== 'admin' && $transaction->user_id !== Auth::id()) abort(403);

        if ($transaction->status !== 'dipinjam') {
            return response()->json(['error' => 'Transaksi bukan berstatus dipinjam'], 422);
        }

        return DB::transaction(function() use ($transaction) {
            $transaction->update([
                'status' => 'dikembalikan',
                'tanggal_kembali' => now()->toDateString(),
            ]);

            $book = books::lockForUpdate()->find($transaction->buku_id);
            if ($book) $book->increment('stok', 1);

            $transaction->refresh();
            return response()->json(['message' => 'Book returned', 'data' => $transaction]);
        });
    }

    public function destroy(transaction $transaction)
    {
        // admin or owner can delete
        if (Auth::user()?->role !== 'admin') abort(403);

        // If deleting a dipinjam transaction, restore stock
        if ($transaction->status === 'dipinjam') {
            $book = books::find($transaction->buku_id);
            if ($book) $book->increment('stok', 1);
        }
        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted']);
    }
}
