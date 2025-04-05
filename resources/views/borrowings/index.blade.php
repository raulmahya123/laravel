@extends('layouts.app')

@section('content')
    <h2 class="text-xl font-bold mb-6">📦 Daftar Peminjaman</h2>

    <table class="w-full table-auto border">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2">Judul</th>
                <th class="p-2">User</th>
                <th class="p-2">Tanggal Pinjam</th>
                <th class="p-2">Status</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $b)
                <tr class="border-t">
                    <td class="p-2">{{ $b->book->title }}</td>
                    <td class="p-2">{{ $b->user->name }}</td>
                    <td class="p-2">{{ $b->borrowed_at->format('d M Y') }}</td>
                    <td class="p-2">
                        @if($b->status === 'borrowed')
                            <span class="text-yellow-600">Dipinjam</span>
                        @else
                            <span class="text-green-600">Dikembalikan</span>
                        @endif
                    </td>
                    <td class="p-2">
                        @if($b->status === 'borrowed')
                            <form method="POST" action="{{ route('admin.borrowings.return', $b) }}">
                                @csrf
                                <button class="bg-green-600 text-white px-3 py-1 rounded">✔️ Kembalikan</button>
                            </form>
                        @else
                            <span class="text-xs text-gray-500">{{ $b->returned_at->format('d M Y') }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
