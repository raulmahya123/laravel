<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\Book;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/pdf', [BorrowingController::class, 'exportPDF'])->name('dashboard.pdf');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // View all books
    Route::get('/books', [BookController::class, 'index'])->name('books.index');

    // Borrow books (user)
    Route::post('/books/{book}/borrow', [BorrowingController::class, 'store'])->name('books.borrow');

    // Admin-only routes
    Route::middleware(['is_admin'])->group(function () {
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
        Route::get('/admin/borrowings', [BorrowingController::class, 'index'])->name('admin.borrowings.index');
        Route::post('/admin/borrowings/{borrowing}/return', [BorrowingController::class, 'return'])->name('admin.borrowings.return');
        Route::patch('/borrowings/{borrowing}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve');
        Route::patch('/borrowings/{borrowing}/returned', [BorrowingController::class, 'markAsReturned'])->name('borrowings.returned');
        Route::patch('/borrowings/{borrowing}/borrowed', [BorrowingController::class, 'markAsBorrowed'])->name('borrowings.borrowed');


    });

    // 📌 IMPORTANT: Route ini HARUS di bawah
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
});

require __DIR__.'/auth.php';
