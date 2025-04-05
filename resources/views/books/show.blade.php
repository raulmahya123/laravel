@extends('layouts.app')

@section('content')
    <div class="flex gap-6">
        @if($book->cover)
            <img src="{{ asset('storage/' . $book->cover) }}" class="w-64 h-80 object-cover rounded shadow">
        @endif

        <div class="flex-1">
            <h2 class="text-2xl font-bold mb-2">{{ $book->title }}</h2>
            <p class="text-gray-600 mb-1"><strong>Penulis:</strong> {{ $book->author }}</p>
            <p class="text-gray-600 mb-2"><strong>Stok:</strong> {{ $book->stock }}</p>
            <p class="text-gray-700">{{ $book->description }}</p>

            <div class="mt-6">
                @if(auth()->user()->is_admin)
                    <a href="{{ route('books.edit', $book) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit Buku</a>
                @else
                    <form method="POST" action="{{ route('books.borrow', $book) }}" class="inline-block">
                        @csrf
                        <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700" {{ $book->stock < 1 ? 'disabled' : '' }}>
                            Pinjam Buku
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
