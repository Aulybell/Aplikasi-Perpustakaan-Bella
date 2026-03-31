<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body {
            font-family: Calibri, Arial, sans-serif;
            margin: 30px;
            font-size: 13px;
        }
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }
        .info {
            text-align: center;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .filter-info {
            text-align: center;
            margin-bottom: 15px;
            font-size: 12px;
            color: #555;
        }
        .filter-badge {
            display: inline-block;
            background: #e8f0fe;
            border: 1px solid #bcd0fb;
            border-radius: 4px;
            padding: 2px 10px;
            font-weight: bold;
            color: #1a56db;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #dce8fb;
            font-weight: bold;
            color: #1a3a6e;
        }
        table, th, td {
            border: 1px solid #888;
        }
        th, td {
            padding: 6px;
            text-align: center;
        }
        td.text-left {
            text-align: left;
        }
        .total-row {
            font-weight: bold;
            background-color: #e9e9e9;
        }
        .ttd-section {
            width: 100%;
            margin-top: 40px;
            overflow: hidden;
        }
        .ttd-box {
            float: right;
            text-align: center;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

<h2>LAPORAN PEMINJAMAN BUKU</h2>
<div class="info">Perpustakaan ReadMe</div>
<div class="info">Dicetak pada: {{ now()->format('d M Y H:i') }}</div>

{{-- Filter tanggal info --}}
<div class="filter-info">
    @if($dateFrom || $dateTo)
        Periode:
        <span class="filter-badge">
            {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d M Y') : '—' }}
            &nbsp;s/d&nbsp;
            {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d M Y') : '—' }}
        </span>
    @else
        Periode: <span class="filter-badge">Semua Data</span>
    @endif
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Judul Buku</th>
            <th>Nama</th>
            <th>No Telp</th>
            <th>Tgl Pinjam</th>
            <th>Estimasi Kembali</th>
            <th>Tgl Kembali</th>
            <th>Status</th>
            <th>Denda (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @php $totalDenda = 0; @endphp
        @forelse($data as $index => $item)
            @php $totalDenda += $item->denda ?? 0; @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left">{{ $item->buku->judul ?? '-' }}</td>
                <td class="text-left">{{ $item->nama_peminjam }}</td>
                <td>{{ $item->no_telp }}</td>
                <td>{{ $item->tanggal_pinjam?->format('d/m/Y') }}</td>
                <td>{{ $item->perkiraan_kembali?->format('d/m/Y') }}</td>
                <td>{{ $item->tanggal_kembali?->format('d/m/Y') }}</td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>{{ number_format($item->denda ?? 0, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="text-align:center;color:#999;padding:16px;">
                    Tidak ada data pada periode ini
                </td>
            </tr>
        @endforelse
        <tr class="total-row">
            <td colspan="8" style="text-align:right;">TOTAL DENDA</td>
            <td>Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

<div class="ttd-section">
    <div class="ttd-box">
        <p>Mengetahui,</p>
        <br><br><br>
        <p>_________________________</p>
        <p>Petugas Perpustakaan</p>
    </div>
</div>

<br style="clear:both;">
<div class="no-print" style="margin-top:20px;">
    <button onclick="window.print()"
            style="padding:8px 20px;background:#2563EB;color:white;border:none;border-radius:8px;font-size:13px;cursor:pointer;">
        🖨️ Cetak Ulang
    </button>
</div>

</body>
</html>