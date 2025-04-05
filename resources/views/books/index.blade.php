@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="flex flex-col md:flex-row justify-between items-center mb-4">
        <h2 class="text-xl font-semibold mb-2 md:mb-0">📚 Daftar Buku</h2>
        @if(auth()->user()->is_admin)
            <a href="{{ route('books.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Buku</a>
        @endif
    </div>

    <div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        @foreach($books as $book)
            <div class="bg-white p-4 rounded shadow">
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}" class="w-full h-48 object-cover mb-3 rounded">
                @endif
                <h3 class="font-bold text-lg">{{ $book->title }}</h3>
                <p class="text-sm text-gray-600">{{ $book->author }}</p>
                <p class="text-xs text-gray-500 mt-2">Stok: {{ $book->stock }}</p>

                <div class="mt-4 flex justify-between items-center">
                    <a href="{{ route('books.show', $book) }}" class="text-blue-600 hover:underline">Detail</a>

                    @if(auth()->user()->is_admin)
                        <div class="flex gap-2">
                            <a href="{{ route('books.edit', $book) }}" class="text-yellow-500 hover:underline">Edit</a>
                            <form method="POST" action="{{ route('books.destroy', $book) }}">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:underline" onclick="return confirm('Hapus buku ini?')">Hapus</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('books.borrow', $book) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                📥 Pinjam Buku
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $books->links() }}
    </div>
</div>
@endsection
