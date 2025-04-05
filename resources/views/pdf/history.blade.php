<h2>Riwayat Peminjaman Buku</h2>
<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Tanggal Pinjam</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($history as $b)
            <tr>
                <td>{{ $b->book->title ?? '-' }}</td>
                <td>{{ $b->borrowed_at }}</td>
                <td>{{ $b->status }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Tidak ada data.</td>
            </tr>
        @endforelse
    </tbody>
</table>
