<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $transactions = Transaction::with(['book', 'user'])->get();
        } else {
            $transactions = Transaction::with(['book', 'user'])->where('user_id', Auth::id())->get();
        }

        return response()->json(['data' => $transactions]);
    }

    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $users = User::where('role', 'anggota')->get();
        $books = Book::where('stok_buku', '>', 0)->get();
        return response()->json(['users' => $users, 'books' => $books]);
    }

    public function store(Request $request)
    {
        $rules = [
            'buku_id' => 'required|exists:books,id',
            'tanggal_peminjaman' => 'required|date',
            'jatuh_tempo' => 'required|date|after:tanggal_peminjaman',
        ];
        if (Auth::user()?->role === 'admin') {
            $rules['user_id'] = 'required|exists:users,id';
        }

        $data = $request->validate($rules);

        if (Auth::user()?->role !== 'admin') {
            $data['user_id'] = Auth::id();
        }

        $data['status'] = 'dipinjam';

        return DB::transaction(function() use ($data) {
            $book = Book::lockForUpdate()->find($data['buku_id']);
            if (!$book || $book->stok_buku <= 0) {
                return response()->json(['error' => 'Buku tidak tersedia'], 422);
            }

            $transaction = Transaction::create($data);
            $book->decrement('stok_buku', 1);
            return response()->json(['message' => 'Transaction created', 'data' => $transaction->load('user', 'book')], 201);
        });
    }

    public function show(Transaction $transaction)
    {
        if (Auth::user()?->role !== 'admin' && $transaction->user_id !== Auth::id()) abort(403);
        return response()->json(['data' => $transaction->load('book', 'user', 'reports', 'visits')]);
    }

    public function edit(Transaction $transaction)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $users = User::where('role', 'anggota')->get();
        $books = Book::get();
        return response()->json(['data' => $transaction, 'users' => $users, 'books' => $books]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'tanggal_peminjaman' => 'required|date',
            'jatuh_tempo' => 'required|date|after:tanggal_peminjaman',
            'tanggal_pengembalian' => 'nullable|date|after_or_equal:tanggal_peminjaman',
            'status' => 'required|in:dipinjam,dikembalikan',
        ]);

        // If status changes from dipinjam to dikembalikan, increase stock
        if ($transaction->status === 'dipinjam' && $data['status'] === 'dikembalikan') {
            $transaction->book->increment('stok_buku');
        }

        $transaction->update($data);
        return response()->json(['message' => 'Transaction updated', 'data' => $transaction->load('user', 'book')]);
    }

    public function returnBook(Request $request, Transaction $transaction)
    {
        if (Auth::user()?->role !== 'admin' && $transaction->user_id !== Auth::id()) abort(403);

        if ($transaction->status !== 'dipinjam') {
            return response()->json(['error' => 'Transaksi bukan berstatus dipinjam'], 422);
        }

        return DB::transaction(function() use ($transaction) {
            $transaction->update([
                'status' => 'dikembalikan',
                'tanggal_pengembalian' => now()->toDateString(),
            ]);

            $book = Book::lockForUpdate()->find($transaction->buku_id);
            if ($book) $book->increment('stok_buku', 1);

            $transaction->refresh();
            return response()->json(['message' => 'Book returned', 'data' => $transaction->load('book', 'user')]);
        });
    }

    public function destroy(Transaction $transaction)
    {
        // admin or owner can delete
        if (Auth::user()?->role !== 'admin') abort(403);

        // If deleting a dipinjam transaction, restore stock
        if ($transaction->status === 'dipinjam') {
            $book = Book::find($transaction->buku_id);
            if ($book) $book->increment('stok_buku', 1);
        }
        $transaction->delete();
        return response()->json(['message' => 'Transaction deleted']);
    }

    public function myTransactions()
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)->with('book')->get();
        return response()->json(['data' => $transactions]);
    }

    public function search()
    {
        $user = Auth::user();
        $transactions = Transaction::where('user_id', $user->id)
            ->where('tanggal_peminjaman', 'like', '%' . request('q') . '%')
            ->where('tanggal_pengembalian', 'like', '%' . request('q') . '%')
            ->where('tanggal_jatuh_tempo', 'like', '%' . request('q') . '%')
            ->where('nama_buku', 'like', '%' . request('q') . '%')
            ->with('book')
            ->get();
        return response()->json(['data' => $transactions]);
    }
}
