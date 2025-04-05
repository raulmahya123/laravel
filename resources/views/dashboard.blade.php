<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Start of Custom Content -->
            <h2 class="text-2xl font-bold mb-6">📊 Dashboard</h2>

            @if (auth()->user()->is_admin)
            {{-- Admin Dashboard --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-white">
                <div class="bg-blue-600 p-6 rounded shadow">
                    <h3 class="text-xl">Total Buku</h3>
                    <p class="text-3xl font-bold">{{ \App\Models\Book::count() }}</p>
                </div>
                <div class="bg-green-600 p-6 rounded shadow">
                    <h3 class="text-xl">Peminjaman Aktif</h3>
                    <p class="text-3xl font-bold">{{ \App\Models\Borrowing::where('status', 'borrowed')->count() }}</p>
                </div>
                <div class="bg-purple-600 p-6 rounded shadow">
                    <h3 class="text-xl">Jumlah User</h3>
                    <p class="text-3xl font-bold">{{ \App\Models\User::count() }}</p>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('books.index') }}" class="underline text-blue-700">📚 Kelola Buku</a>
            </div>

            <div class="mt-6">
                <h3 class="text-xl font-semibold">📚 Pengembalian Buku</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm table-auto">
                        <thead>
                            <tr class="text-left border-b">
                                <th class="py-2">📘 Judul Buku</th>
                                <th>User</th>
                                <th>Tanggal Pengembalian</th>
                                <th>Status Pengembalian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Models\Borrowing::whereIn('status', ['pending', 'borrowed', 'returned'])->get() as $borrowing)
                                <tr class="border-b">
                                    <td class="py-2">{{ $borrowing->book->title }}</td>
                                    <td>{{ $borrowing->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($borrowing->returned_at)->format('d M Y') }}</td>
                                    <td>
                                        <span class="px-2 py-1 rounded text-white 
                                            @if ($borrowing->status == 'pending') 
                                                bg-red-500
                                            @elseif ($borrowing->status == 'borrowed') 
                                                bg-yellow-500
                                            @elseif ($borrowing->status == 'returned') 
                                                bg-green-500
                                            @endif">
                                            {{ ucfirst($borrowing->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($borrowing->status == 'pending')
                                            <!-- Approve Borrowing Button -->
                                            <form action="{{ route('borrowings.approve', $borrowing->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                                                    Approve Borrowing
                                                </button>
                                            </form>
                                        @elseif ($borrowing->status == 'borrowed')
                                            <!-- Mark as Returned Button -->
                                            <form action="{{ route('borrowings.returned', $borrowing->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                                    Mark as Returned
                                                </button>
                                            </form>
                                        @elseif ($borrowing->status == 'returned')
                                            <!-- If already returned, no action is needed -->
                                            <span class="text-gray-500">Sudah dikembalikan</span>
                                        @else
                                            <span class="text-gray-500">Status tidak valid</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @else
            {{-- User Dashboard --}}
            <div class="mb-4">
                <h3 class="text-xl font-semibold">📦 Riwayat Peminjaman Anda</h3>
            </div>

            <div class="bg-white shadow rounded p-4">
                @php
                    $history = auth()->user()->borrowings()->with('book')->latest()->get();
                @endphp
                <div class="mt-6 text-center">
                    <a href="{{ route('books.index') }}"
                       class="inline-block bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
                        📥 Lihat & Pinjam Buku
                    </a>
                </div>
                @if ($history->isEmpty())
                    <p class="text-gray-500">Belum ada riwayat peminjaman.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm table-auto">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="py-2">📘 Judul</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($history as $b)
                                    <tr class="border-b">
                                        <td class="py-2">{{ $b->book->title }}</td>
                                        <td>{{ \Carbon\Carbon::parse($b->borrowed_at)->format('d M Y') }}</td>
                                        <td>
                                            <span class="px-2 py-1 rounded text-white 
                                                @if ($b->status == 'borrowed') 
                                                    bg-yellow-500
                                                @elseif ($b->status == 'returned') 
                                                    bg-green-500
                                                @elseif ($b->status == 'pending') 
                                                    bg-red-500
                                                @endif">
                                                {{ ucfirst($b->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            @endif
            <!-- End of Custom Content -->
        </main>
    </div>
</body>
</html>
