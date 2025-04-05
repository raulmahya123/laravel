<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📚 BookLend — Peminjaman Buku Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex flex-col text-gray-800">

    {{-- Wrapper dengan gradient --}}
    <div class="flex-grow bg-gradient-to-br from-blue-50 to-white flex flex-col">

        {{-- Navbar --}}
        <nav class="flex justify-between items-center px-6 py-4 bg-white shadow">
            <h1 class="text-2xl font-bold text-blue-600">📚 BookLend</h1>
            <div class="flex gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-blue-700 hover:underline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-700 font-semibold">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-700 font-semibold">Register</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="flex-1 flex flex-col items-center justify-center text-center py-20 px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-blue-800 mb-4 leading-tight">Sistem Peminjaman Buku Digital</h2>
            <p class="text-gray-600 text-lg max-w-2xl mb-8">Kelola, pinjam, dan lacak buku perpustakaanmu dengan mudah dan cepat menggunakan platform online kami.</p>
            <div class="flex flex-col sm:flex-row gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-blue-600 text-white px-6 py-3 rounded shadow hover:bg-blue-700 transition">Go to Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-3 rounded shadow hover:bg-blue-700 transition">Daftar Sekarang</a>
                    <a href="{{ route('login') }}" class="bg-gray-100 text-blue-700 px-6 py-3 rounded shadow hover:bg-gray-200 transition">Login</a>
                @endauth
            </div>
        </main>
    </div>
    <section class="mt-16 max-w-7xl mx-auto px-4 sm:px-6">
        {{-- Available Books Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <h2 class="text-xl sm:text-2xl font-bold text-blue-800">📚 Buku Tersedia</h2>
    
            <form method="GET" action="" class="flex items-center">
                <label for="sort_available" class="mr-2 text-sm text-gray-600">Urutkan:</label>
                <select name="sort_available" id="sort_available" onchange="this.form.submit()" class="text-sm border rounded px-3 py-1">
                    <option value="">Default</option>
                    <option value="title_asc">Judul A-Z</option>
                    <option value="title_desc">Judul Z-A</option>
                    <option value="stock_high">Stok Terbanyak</option>
                    <option value="stock_low">Stok Tersedikit</option>
                </select>
            </form>
        </div>
    
        <div class="flex overflow-x-auto space-x-4 snap-x snap-mandatory pb-4">
            @php
                $availableBooks = \App\Models\Book::where('stock', '>', 0);
    
                if(request('sort_available') == 'title_asc') {
                    $availableBooks->orderBy('title', 'asc');
                } elseif(request('sort_available') == 'title_desc') {
                    $availableBooks->orderBy('title', 'desc');
                } elseif(request('sort_available') == 'stock_high') {
                    $availableBooks->orderBy('stock', 'desc');
                } elseif(request('sort_available') == 'stock_low') {
                    $availableBooks->orderBy('stock', 'asc');
                }
    
                $availableBooks = $availableBooks->take(10)->get();
            @endphp
    
            @foreach($availableBooks as $book)
                <div class="flex-none snap-start w-56 sm:w-64 bg-white rounded shadow p-4">
                    @if($book->cover)
                        <img src="{{ asset('storage/' . $book->cover) }}" class="h-40 w-full object-cover rounded mb-3">
                    @endif
                    <h3 class="font-semibold text-lg truncate">{{ $book->title }}</h3>
                    <p class="text-sm text-gray-500 truncate">Penulis: {{ $book->author }}</p>
                    <span class="text-green-600 text-sm">Stok: {{ $book->stock }}</span>
                </div>
            @endforeach
        </div>
    </section>
    
    {{-- Pending Books Section --}}
    <section class="mt-16 max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <h2 class="text-xl sm:text-2xl font-bold text-blue-800">📚 Buku Pending</h2>
    
            <form method="GET" action="" class="flex items-center">
                <label for="sort_pending" class="mr-2 text-sm text-gray-600">Urutkan:</label>
                <select name="sort_pending" id="sort_pending" onchange="this.form.submit()" class="text-sm border rounded px-3 py-1">
                    <option value="">Default</option>
                    <option value="title_asc">Judul A-Z</option>
                    <option value="title_desc">Judul Z-A</option>
                </select>
            </form>
        </div>
    
        <div class="flex overflow-x-auto space-x-4 snap-x snap-mandatory pb-4">
            @php
                $pendingBooks = \App\Models\Book::whereHas('borrowings', function ($query) {
                    $query->where('status', 'pending');
                });
    
                if(request('sort_pending') == 'title_asc') {
                    $pendingBooks->orderBy('title', 'asc');
                } elseif(request('sort_pending') == 'title_desc') {
                    $pendingBooks->orderBy('title', 'desc');
                }
    
                $pendingBooks = $pendingBooks->take(10)->get();
            @endphp
    
            @foreach($pendingBooks as $borrowing)
                <div class="flex-none snap-start w-56 sm:w-64 bg-white rounded shadow p-4">
                    @if($borrowing->book->cover)
                        <img src="{{ asset('storage/' . $borrowing->book->cover) }}" class="h-40 w-full object-cover rounded mb-3">
                    @endif
                    <h3 class="font-semibold text-lg truncate">{{ $borrowing->book->title }}</h3>
                    <p class="text-sm text-gray-500 truncate">Penulis: {{ $borrowing->book->author }}</p>
                    <span class="text-red-600 text-sm">Status: Pending</span>
                </div>
            @endforeach
        </div>
    </section>
    
    {{-- Borrowed Books Section --}}
    <section class="mt-16 max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <h2 class="text-xl sm:text-2xl font-bold text-blue-800">📚 Buku Dipinjam</h2>
    
            <form method="GET" action="" class="flex items-center">
                <label for="sort_borrowed" class="mr-2 text-sm text-gray-600">Urutkan:</label>
                <select name="sort_borrowed" id="sort_borrowed" onchange="this.form.submit()" class="text-sm border rounded px-3 py-1">
                    <option value="">Default</option>
                    <option value="title_asc">Judul A-Z</option>
                    <option value="title_desc">Judul Z-A</option>
                </select>
            </form>
        </div>
    
        <div class="flex overflow-x-auto space-x-4 snap-x snap-mandatory pb-4">
            @php
                $borrowedBooks = \App\Models\Borrowing::where('status', 'borrowed')
                    ->with('book'); // Load the related book
    
                if(request('sort_borrowed') == 'title_asc') {
                    $borrowedBooks->orderBy('books.title', 'asc');
                } elseif(request('sort_borrowed') == 'title_desc') {
                    $borrowedBooks->orderBy('books.title', 'desc');
                }
    
                $borrowedBooks = $borrowedBooks->take(10)->get();
            @endphp
    
            @foreach($borrowedBooks as $borrowing)
                <div class="flex-none snap-start w-56 sm:w-64 bg-white rounded shadow p-4">
                    @if($borrowing->book->cover)
                        <img src="{{ asset('storage/' . $borrowing->book->cover) }}" class="h-40 w-full object-cover rounded mb-3">
                    @endif
                    <h3 class="font-semibold text-lg truncate">{{ $borrowing->book->title }}</h3>
                    <p class="text-sm text-gray-500 truncate">Penulis: {{ $borrowing->book->author }}</p>
                    <span class="text-yellow-600 text-sm">Status: Dipinjam</span>
                </div>
            @endforeach
        </div>
    </section>
    
    


    {{-- Footer tetap di luar gradient container --}}
    <footer class="mt-auto bg-white border-t w-full text-center px-4 py-6 sm:py-8 text-sm text-gray-500">
        <div class="max-w-4xl mx-auto space-y-2">
            <p>&copy; {{ date('Y') }} <span class="font-semibold text-blue-700">BookLend</span> – Aplikasi Peminjaman Buku.</p>
            <p>
                Dibuat dengan <span class="text-red-500">❤️</span> menggunakan 
                <a href="https://laravel.com" target="_blank" class="text-blue-600 hover:underline">Laravel</a> & 
                <a href="https://tailwindcss.com" target="_blank" class="text-blue-600 hover:underline">TailwindCSS</a>.
            </p>
        </div>
    </footer>
</body>

</html>
