<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use App\Models\Book;

class BorrowingController extends Controller
{
    public function store(Book $book)
    {
        // Cegah pinjam lagi buku yang sama kalau status masih borrowed
        $alreadyBorrowed = $book
            ->borrowings()
            ->where('user_id', auth()->id())
            ->where('status', 'pending', 'borrowed')
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'Kamu sudah meminjam buku ini.');
        }

        if ($book->stock < 1) {
            return back()->with('error', 'Stok buku habis.');
        }

        $book->decrement('stock');

        Borrowing::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'borrowed_at' => now(),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Buku berhasil dipinjam!');
    }

    public function index()
    {
        $borrowings = Borrowing::with('user', 'book')->latest()->get();
        return view('borrowings.index', compact('borrowings'));
    }

    public function return(Borrowing $borrowing)
    {
        $borrowing->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);

        $borrowing->book->increment('stock');

        return back()->with('success', 'Buku berhasil dikembalikan.');
    }

    public function approve($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        // Approve the borrowing by setting status to 'borrowed'
        $borrowing->update(['status' => 'borrowed']);
        
        return redirect()->back()->with('success', 'Peminjaman disetujui, status diperbarui ke "borrowed".');
    }
    
    public function markAsReturned($id)
    {
        $borrowing = Borrowing::findOrFail($id);
        // Mark as returned and set the returned_at date
        $borrowing->update(['status' => 'returned', 'returned_at' => now()]);
        
        return redirect()->back()->with('success', 'Buku telah dikembalikan.');
    }
    public function markAsBorrowed($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        // Update status kembali menjadi borrowed
        $borrowing->update(['status' => 'borrowed', 'returned_at' => null]);

        return redirect()->back()->with('success', 'Buku telah kembali dipinjam.');
    }
}
