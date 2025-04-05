@extends('layouts.app')

@section('content')
    <h2 class="text-xl font-semibold mb-4">📘 Tambah Buku</h2>

    <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block font-medium">Judul</label>
            <input type="text" name="title" class="w-full border px-4 py-2 rounded" required>
        </div>
        <div>
            <label class="block font-medium">Penulis</label>
            <input type="text" name="author" class="w-full border px-4 py-2 rounded" required>
        </div>
        <div>
            <label class="block font-medium">Deskripsi</label>
            <textarea name="description" class="w-full border px-4 py-2 rounded"></textarea>
        </div>
        <div>
            <label class="block font-medium">Cover</label>
            <input type="file" name="cover" class="w-full border px-4 py-2 rounded">
        </div>
        <div>
            <label class="block font-medium">Stok</label>
            <input type="number" name="stock" class="w-full border px-4 py-2 rounded" required>
        </div>
        <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan</button>
    </form>
@endsection
