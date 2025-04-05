<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index() {
        $books = Book::latest()->paginate(10);
        return view('books.index', compact('books'));
    }

    public function create() {
        return view('books.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
            'stock' => 'required|integer|min:0'
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Book::create($validated);
        return redirect()->route('books.index')->with('success', 'Book added.');
    }

    public function show(Book $book) {
        return view('books.show', compact('book'));
    }

    public function edit(Book $book) {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book) {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
            'stock' => 'required|integer|min:0'
        ]);

        if ($request->hasFile('cover')) {
            if ($book->cover) Storage::disk('public')->delete($book->cover);
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $book->update($validated);
        return redirect()->route('books.index')->with('success', 'Book updated.');
    }

    public function destroy(Book $book) {
        if ($book->cover) Storage::disk('public')->delete($book->cover);
        $book->delete();
        return back()->with('success', 'Book deleted.');
    }
}
