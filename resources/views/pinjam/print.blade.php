<!DOCTYPE html>
<html>
<head>
    <title>Struk Peminjaman</title>
    <style>
        body {
            font-family: monospace;
            width: 320px;
            margin: auto;
            font-size: 14px;
        }
        .center {
            text-align: center;
        }
        hr {
            border-top: 1px dashed black;
            margin: 8px 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
        }
        .bold {
            font-weight: bold;
        }
        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

<div class="center">
    <h3>PERPUSTAKAAN DIGITAL</h3>
    <p>Jl. Pendidikan No. 123</p>
    <p>Telp: 0812-3456-7890</p>
</div>

<hr>

<div class="row">
    <span>No Transaksi</span>
    <span>#{{ $pinjam->id }}</span>
</div>

<div class="row">
    <span>Tanggal Cetak</span>
    <span>{{ now()->format('d M Y H:i') }}</span>
</div>

<hr>

<p class="bold">DATA PEMINJAM</p>

<div class="row">
    <span>Nama</span>
    <span>{{ $pinjam->nama_peminjam }}</span>
</div>

<div class="row">
    <span>No Telp</span>
    <span>{{ $pinjam->no_telp }}</span>
</div>

<hr>

<p class="bold">DETAIL PEMINJAMAN</p>

<div class="row">
    <span>Judul Buku</span>
</div>
<p>{{ $pinjam->buku->judul }}</p>

<div class="row">
    <span>Tanggal Pinjam</span>
    <span>{{ $pinjam->tanggal_pinjam?->format('d M Y') }}</span>
</div>

<div class="row">
    <span>Estimasi Kembali</span>
    <span>{{ $pinjam->perkiraan_kembali?->format('d M Y') }}</span>
</div>

<div class="row">
    <span>Tanggal Kembali</span>
    <span>{{ $pinjam->tanggal_kembali?->format('d M Y') }}</span>
</div>

<hr>

<p class="bold">RINCIAN BIAYA</p>

<div class="row">
    <span>Denda</span>
    <span>Rp {{ number_format($pinjam->denda ?? 0,0,',','.') }}</span>
</div>

<hr>

<div class="row bold">
    <span>TOTAL BAYAR</span>
    <span>Rp {{ number_format($pinjam->denda ?? 0,0,',','.') }}</span>
</div>

<hr>

<div class="center">
    <p>Status: {{ strtoupper($pinjam->status) }}</p>
    <p>--------------------------------</p>
    <p>Terima Kasih Telah Meminjam</p>
    <p>Harap Kembalikan Tepat Waktu</p>
</div>

<button onclick="window.print()">Cetak Ulang</button>

</body>
</html>