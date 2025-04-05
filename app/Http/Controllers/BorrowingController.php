<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use App\Models\Book;
use Barryvdh\DomPDF\Facade\Pdf;

class BorrowingController extends Controller
{

    public function exportPDF()
    {
        $history = \App\Models\Borrowing::with('book')->get(); // ambil semua data
        // Tes output
        // dd($history);
    
        $pdf = Pdf::loadView('pdf.history', compact('history'));
        return $pdf->download('riwayat-peminjaman.pdf');
    }
    

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
        // Ambil data peminjaman beserta relasi buku-nya
        $borrowing = \App\Models\Borrowing::with('book')->findOrFail($id);
    
        // Update status jadi returned + set tanggal kembali
        $borrowing->update([
            'status' => 'returned',
            'returned_at' => now(),
        ]);
    
        // Tambah kembali stok buku
        if ($borrowing->book) {
            $borrowing->book->increment('stock');
        }
    
        return redirect()->back()->with('success', 'Buku telah dikembalikan dan stok diperbarui.');
    }
    
    public function markAsBorrowed($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        // Update status kembali menjadi borrowed
        $borrowing->update(['status' => 'borrowed', 'returned_at' => null]);

        return redirect()->back()->with('success', 'Buku telah kembali dipinjam.');
    }
}
