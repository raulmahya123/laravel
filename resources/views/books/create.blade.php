@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-4">
    <h2 class="text-xl font-semibold mb-4 text-center">📘 Tambah Buku</h2>

    <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        <div>
            <label class="block font-medium mb-1">Judul</label>
            <input type="text" name="title" class="w-full border px-4 py-2 rounded" required>
        </div>
        <div>
            <label class="block font-medium mb-1">Penulis</label>
            <input type="text" name="author" class="w-full border px-4 py-2 rounded" required>
        </div>
        <div>
            <label class="block font-medium mb-1">Deskripsi</label>
            <textarea name="description" class="w-full border px-4 py-2 rounded"></textarea>
        </div>
        <div>
            <label class="block font-medium mb-1">Cover</label>
            <input type="file" name="cover" class="w-full border px-4 py-2 rounded">
        </div>
        <div>
            <label class="block font-medium mb-1">Stok</label>
            <input type="number" name="stock" class="w-full border px-4 py-2 rounded" required>
        </div>
        <div class="text-center">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
