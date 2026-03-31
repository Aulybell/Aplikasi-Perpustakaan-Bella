@extends('layout.template')
@section('title', 'Keranjang - ReadMe')

@section('content')
<style>
    .krj-wrap {
        max-width: 1020px;
        margin: 0 auto;
        padding: 24px 20px 80px;
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
    }
    .krj-breadcrumb {
        display: flex; align-items: center; gap: 5px;
        font-size: .75rem; color: #94a3b8; margin-bottom: 10px;
    }
    .krj-breadcrumb a { color: #2563eb; text-decoration: none; font-weight: 500; }
    .krj-wrap h1 {
        font-size: 1.5rem; font-weight: 800;
        letter-spacing: -.03em; color: #0f172a; margin-bottom: 4px;
    }
    .krj-sub { color: #64748b; font-size: .875rem; margin-bottom: 22px; }

    .krj-alert {
        display: flex; align-items: center; gap: 10px;
        padding: 11px 15px; border-radius: 10px;
        margin-bottom: 14px; font-size: .855rem; font-weight: 500;
    }
    .krj-alert-success { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
    .krj-alert-error   { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }
    .krj-alert-warning { background:#fefce8; color:#ca8a04; border:1px solid #fef08a; }

    .krj-grid {
        display: grid; grid-template-columns: 1fr 310px;
        gap: 18px; align-items: start;
    }
    @media (max-width: 820px) { .krj-grid { grid-template-columns: 1fr; } }

    .krj-card {
        background: #fff; border-radius: 14px;
        box-shadow: 0 2px 16px rgba(37,99,235,.08), 0 1px 4px rgba(15,23,42,.04);
        border: 1px solid #e8f1fb; overflow: hidden;
    }
    .krj-toolbar {
        padding: 12px 18px; border-bottom: 1px solid #e8f1fb;
        display: flex; align-items: center; justify-content: space-between;
        gap: 10px; flex-wrap: wrap; background: #fafcff;
    }
    .krj-check-all { display: flex; align-items: center; gap: 9px; cursor: pointer; user-select: none; }
    .krj-check-all input[type="checkbox"] { width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer; }
    .krj-check-label { font-weight: 600; font-size: .875rem; color: #0f172a; }
    .krj-chip {
        font-size: .72rem; font-weight: 700; color: #2563eb;
        background: #eff6ff; border: 1px solid #bfdbfe;
        padding: 2px 9px; border-radius: 20px;
    }
    .krj-btn-clear {
        display: inline-flex; align-items: center; gap: 4px;
        background: none; border: 1px solid #e2e8f0;
        color: #64748b; font-size: .78rem; font-weight: 500; font-family: inherit;
        padding: 5px 11px; border-radius: 7px; cursor: pointer; transition: all .15s;
    }
    .krj-btn-clear:hover { border-color:#ef4444; color:#ef4444; background:#fef2f2; }

    .krj-item {
        display: flex; align-items: flex-start; gap: 13px;
        padding: 15px 18px; border-bottom: 1px solid #e8f1fb; transition: background .14s;
    }
    .krj-item:last-child { border-bottom: none; }
    .krj-item:hover { background: #f5f9ff; }
    .krj-item.out-of-stock { opacity: .5; }

    .krj-item-cb { padding-top: 3px; flex-shrink: 0; }
    .krj-item-cb input[type="checkbox"] { width: 17px; height: 17px; accent-color: #2563eb; cursor: pointer; }
    .krj-item-cb input[type="checkbox"]:disabled { opacity:.3; cursor:not-allowed; }

    .krj-cover {
        width: 62px; height: 84px; object-fit: cover;
        border-radius: 7px; flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(15,23,42,.12); background: #eff6ff;
    }
    .krj-info { flex: 1; min-width: 0; }
    .krj-badge {
        display: inline-flex; font-size: .67rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .07em;
        color: #2563eb; background: #eff6ff; border: 1px solid #bfdbfe;
        padding: 2px 7px; border-radius: 4px; margin-bottom: 5px;
    }
    .krj-judul {
        font-size: .975rem; font-weight: 700; color: #0f172a;
        letter-spacing: -.02em; line-height: 1.25;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .krj-meta { margin-top: 3px; font-size: .78rem; color: #64748b; line-height: 1.65; }
    .krj-stok {
        margin-top: 7px; display: inline-flex; align-items: center; gap: 4px;
        font-size: .72rem; font-weight: 600; padding: 3px 9px; border-radius: 20px;
    }
    .krj-stok-ada   { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
    .krj-stok-habis { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }

    .krj-item-action { flex-shrink: 0; }
    .krj-btn-hapus {
        display: inline-flex; align-items: center; gap: 4px;
        background: none; border: 1px solid #e2e8f0;
        color: #94a3b8; font-family: inherit;
        font-size: .75rem; font-weight: 500; padding: 5px 10px;
        border-radius: 7px; cursor: pointer; transition: all .15s;
    }
    .krj-btn-hapus:hover { border-color:#ef4444; color:#ef4444; background:#fef2f2; }

    .krj-empty { text-align: center; padding: 52px 24px; }
    .krj-empty-icon {
        width: 72px; height: 72px; background: #eff6ff; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.9rem; margin: 0 auto 16px; border: 2px solid #bfdbfe;
    }
    .krj-empty h3 { font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
    .krj-empty p  { color: #64748b; font-size: .855rem; margin-bottom: 20px; line-height: 1.6; }
    .krj-btn-cari {
        display: inline-flex; align-items: center; gap: 6px;
        background: #2563eb; color: #fff; text-decoration: none;
        padding: 10px 20px; border-radius: 10px;
        font-weight: 600; font-size: .855rem; transition: all .18s;
    }
    .krj-btn-cari:hover { background: #1d4ed8; }

    .krj-summary { position: sticky; top: 80px; }
    .krj-sum-head {
        padding: 13px 18px; border-bottom: 1px solid #e8f1fb;
        display: flex; align-items: center; gap: 8px; background: #fafcff;
    }
    .krj-sum-ico {
        width: 30px; height: 30px; background: #eff6ff;
        border-radius: 7px; display: flex; align-items: center; justify-content: center; font-size: .9rem;
    }
    .krj-sum-head h2 { font-size: .95rem; font-weight: 700; color: #0f172a; }
    .krj-sum-body { padding: 16px 18px; }

    .krj-form-group { margin-bottom: 16px; }
    .krj-form-label {
        display: block; font-size: .72rem; font-weight: 700; color: #64748b;
        margin-bottom: 6px; text-transform: uppercase; letter-spacing: .07em;
    }
    .krj-form-input {
        width: 100%; padding: 9px 12px;
        border: 1.5px solid #ddeaf8; border-radius: 10px;
        font-family: inherit; font-size: .855rem;
        color: #0f172a; background: #f4f8ff; outline: none;
        transition: border-color .15s, box-shadow .15s;
    }
    .krj-form-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.10); }

    /* Info batas 14 hari */
    .krj-date-info {
        display: flex; align-items: center; gap: 6px;
        margin-top: 6px; padding: 7px 10px;
        background: #eff6ff; border: 1px solid #bfdbfe;
        border-radius: 7px; font-size: .75rem; color: #2563eb; font-weight: 500;
    }

    .krj-err { color: #ef4444; font-size: .75rem; margin-top: 4px; }

    .krj-preview {
        background: #eff6ff; border: 1px solid #bfdbfe;
        border-radius: 10px; padding: 11px; margin-bottom: 14px; min-height: 46px;
    }
    .krj-preview-lbl {
        font-size: .67rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: .08em; color: #2563eb; margin-bottom: 7px;
    }
    .krj-tag {
        display: inline-flex; align-items: center; gap: 3px;
        background: #fff; border: 1px solid #bfdbfe;
        border-radius: 5px; padding: 2px 8px;
        font-size: .72rem; font-weight: 500; color: #0f172a; margin: 2px;
    }
    .krj-preview-empty { font-size: .78rem; color: #94a3b8; text-align: center; padding: 4px 0; }

    .krj-divider { height: 1px; background: #e8f1fb; margin: 6px 0 10px; }

    .krj-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 6px 0; font-size: .83rem;
    }
    .krj-row .l { color: #64748b; }
    .krj-row .v { font-weight: 600; color: #0f172a; }
    .krj-row .v-blue  { font-weight: 700; color: #2563eb; }
    .krj-row .v-green { font-weight: 700; color: #16a34a; }
    .krj-row .v-red   { font-weight: 700; color: #ef4444; }

    .krj-btn-submit {
        width: 100%; background: #2563eb; color: #fff; border: none;
        border-radius: 10px; padding: 12px 16px;
        font-family: inherit; font-size: .92rem; font-weight: 700;
        cursor: pointer; transition: all .2s;
        display: flex; align-items: center; justify-content: center; gap: 7px;
        margin-top: 14px;
    }
    .krj-btn-submit:hover:not(:disabled) {
        background: #1d4ed8;
        box-shadow: 0 8px 24px rgba(37,99,235,.3);
        transform: translateY(-1px);
    }
    .krj-btn-submit:disabled { background: #cbd5e1; cursor: not-allowed; transform: none; box-shadow: none; }

    .krj-badge-n {
        background: rgba(255,255,255,.25);
        padding: 1px 9px; border-radius: 20px; font-size: .75rem;
    }
    .krj-notice { margin-top: 10px; font-size: .72rem; color: #94a3b8; text-align: center; line-height: 1.6; }

    /* Warning batas hari */
    .krj-limit-warning {
        display: none; align-items: center; gap: 6px;
        margin-top: 6px; padding: 7px 10px;
        background: #fef2f2; border: 1px solid #fecaca;
        border-radius: 7px; font-size: .75rem; color: #b91c1c; font-weight: 500;
    }
    .krj-limit-warning.show { display: flex; }

    @media (max-width: 560px) {
        .krj-wrap { padding: 16px 10px 80px; }
        .krj-item { flex-wrap: wrap; }
        .krj-item-action { width: 100%; display: flex; justify-content: flex-end; margin-top: 8px; }
    }
</style>

<div class="krj-wrap">

    <div class="krj-breadcrumb">
        <a href="{{ url('/') }}">Beranda</a> <span>›</span> <span>Keranjang</span>
    </div>
    <h1>Keranjang Pinjam 🛒</h1>
    <p class="krj-sub">Pilih buku yang ingin Anda pinjam, lalu tentukan tanggal pengembalian.</p>

    @if(session('success'))
        <div class="krj-alert krj-alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="krj-alert krj-alert-error">❌ {{ session('error') }}</div>
    @endif
    @if(session('warning'))
        <div class="krj-alert krj-alert-warning">⚠️ {{ session('warning') }}</div>
    @endif
    @if($errors->has('buku_ids'))
        <div class="krj-alert krj-alert-error">❌ {{ $errors->first('buku_ids') }}</div>
    @endif
    @if($errors->has('tanggal_kembali'))
        <div class="krj-alert krj-alert-error">❌ {{ $errors->first('tanggal_kembali') }}</div>
    @endif

    {{-- Form mengarah ke pinjam/create-multiple --}}
    <form action="{{ route('pinjam.create.multiple') }}" method="GET" id="formPinjam">

        <div class="krj-grid">

            {{-- KOLOM KIRI --}}
            <div>
                <div class="krj-card">

                    @if($keranjangs->isEmpty())
                        <div class="krj-empty">
                            <div class="krj-empty-icon">📭</div>
                            <h3>Keranjang Masih Kosong</h3>
                            <p>Anda belum menambahkan buku ke keranjang.<br>Yuk, jelajahi koleksi buku kami!</p>
                            <a href="{{ route('koleksi') }}" class="krj-btn-cari">🔍 Cari Buku</a>
                        </div>

                    @else

                        <div class="krj-toolbar">
                            <label class="krj-check-all">
                                <input type="checkbox" id="checkAll">
                                <span class="krj-check-label">Pilih Semua</span>
                                <span class="krj-chip" id="selectedCount">0 dipilih</span>
                            </label>
                            <form action="{{ route('keranjang.hapusSemua') }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="krj-btn-clear"
                                    onclick="return confirm('Hapus semua buku dari keranjang?')">
                                    🗑 Kosongkan
                                </button>
                            </form>
                        </div>

                        @foreach($keranjangs as $item)
                            @php
                                $buku    = $item->buku;
                                $stokAda = $buku->stok > 0;
                                $foto    = $buku->foto
                                    ? asset('storage/' . $buku->foto)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($buku->judul)
                                      . '&background=2563eb&color=fff&size=300&bold=true&font-size=0.28';
                            @endphp

                            <div class="krj-item {{ !$stokAda ? 'out-of-stock' : '' }}">
                                <div class="krj-item-cb">
                                    <input
                                        type="checkbox"
                                        name="buku_ids[]"
                                        value="{{ $buku->id }}"
                                        class="item-checkbox"
                                        data-judul="{{ $buku->judul }}"
                                        {{ !$stokAda ? 'disabled' : '' }}
                                    >
                                </div>
                                <img class="krj-cover" src="{{ $foto }}" alt="{{ $buku->judul }}"
                                    loading="lazy"
                                    onerror="this.src='https://ui-avatars.com/api/?name=B&background=bfdbfe&color=2563eb&size=200'">
                                <div class="krj-info">
                                    @if($buku->kategori)
                                        <span class="krj-badge">{{ $buku->kategori->nama_kategori ?? 'Umum' }}</span>
                                    @endif
                                    <div class="krj-judul" title="{{ $buku->judul }}">{{ $buku->judul }}</div>
                                    <div class="krj-meta">
                                        <strong>{{ $buku->pengarang }}</strong><br>
                                        {{ $buku->penerbit }} &middot; {{ $buku->tahun_terbit }}
                                    </div>
                                    <span class="krj-stok {{ $stokAda ? 'krj-stok-ada' : 'krj-stok-habis' }}">
                                        {{ $stokAda ? '● Tersedia (' . $buku->stok . ' stok)' : '● Stok Habis' }}
                                    </span>
                                </div>
                                <div class="krj-item-action">
                                    <form action="{{ route('keranjang.hapus', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="krj-btn-hapus">🗑 Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach

                    @endif
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            @if($keranjangs->isNotEmpty())
            <div>
                <div class="krj-card krj-summary">
                    <div class="krj-sum-head">
                        <div class="krj-sum-ico">📋</div>
                        <h2>Ringkasan Peminjaman</h2>
                    </div>
                    <div class="krj-sum-body">

                        {{-- Info estimasi pengembalian (tanggal pinjam diisi di form berikutnya) --}}
                        <div class="krj-date-info" style="margin-bottom:16px;">
                            📅
                            <span style="margin-top:4px;display:block;">
                                Estimasi kembali <strong>14 hari</strong> dari tanggal pinjam.
                            </span>
                        </div>

                        <div class="krj-preview">
                            <div class="krj-preview-lbl">Buku Dipilih</div>
                            <div id="selectedPreview">
                                <div class="krj-preview-empty">Belum ada buku yang dipilih</div>
                            </div>
                        </div>

                        <div class="krj-row">
                            <span class="l">Total di keranjang</span>
                            <span class="v">{{ $keranjangs->count() }} buku</span>
                        </div>
                        <div class="krj-row">
                            <span class="l">Dipilih</span>
                            <span class="v v-blue" id="summaryCount">0 buku</span>
                        </div>
                        <div class="krj-row">
                            <span class="l">Maks. durasi pinjam</span>
                            <span class="v v-blue">14 hari</span>
                        </div>
                        <div class="krj-divider"></div>
                        <div class="krj-row">
                            <span class="l">Biaya peminjaman</span>
                            <span class="v v-green">Gratis 🎉</span>
                        </div>

                        <button type="submit" class="krj-btn-submit" id="btnPinjam" disabled>
                            Lanjut ke Form Peminjaman →
                            <span class="krj-badge-n" id="btnCount">0</span>
                        </button>

                        <p class="krj-notice">
                            Tanggal pinjam & data peminjam diisi di langkah berikutnya.<br>
                            Estimasi kembali otomatis <strong>+14 hari</strong> dari tanggal pinjam.
                        </p>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </form>
</div>

<script>
(function () {
    var checkAll  = document.getElementById('checkAll');
    var selCount  = document.getElementById('selectedCount');
    var sumCount  = document.getElementById('summaryCount');
    var btnPinjam = document.getElementById('btnPinjam');
    var btnCount  = document.getElementById('btnCount');
    var preview   = document.getElementById('selectedPreview');

    function updateUI() {
        var checked = document.querySelectorAll('.item-checkbox:checked');
        var n = checked.length;

        if (selCount) selCount.textContent = n + ' dipilih';
        if (sumCount) sumCount.textContent = n + ' buku';
        if (btnCount) btnCount.textContent = n;

        // Tombol aktif jika ada minimal 1 buku dipilih
        if (btnPinjam) btnPinjam.disabled = (n === 0);

        if (preview) {
            if (n === 0) {
                preview.innerHTML = '<div class="krj-preview-empty">Belum ada buku yang dipilih</div>';
            } else {
                var html = '';
                checked.forEach(function(cb) {
                    var judul = cb.dataset.judul || 'Buku';
                    var short = judul.length > 24 ? judul.substring(0, 22) + '...' : judul;
                    html += '<span class="krj-tag">📖 ' + short + '</span>';
                });
                preview.innerHTML = html;
            }
        }

        if (checkAll) {
            var enabled = document.querySelectorAll('.item-checkbox:not(:disabled)');
            checkAll.indeterminate = n > 0 && n < enabled.length;
            checkAll.checked = enabled.length > 0 && n === enabled.length;
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            document.querySelectorAll('.item-checkbox:not(:disabled)').forEach(function(cb) {
                cb.checked = checkAll.checked;
            });
            updateUI();
        });
    }

    document.querySelectorAll('.item-checkbox').forEach(function(cb) {
        cb.addEventListener('change', updateUI);
    });

    // Validasi sebelum submit
    var form = document.getElementById('formPinjam');
    if (form) {
        form.addEventListener('submit', function(e) {
            var n = document.querySelectorAll('.item-checkbox:checked').length;
            if (n === 0) {
                e.preventDefault();
                alert('Pilih minimal satu buku untuk dipinjam!');
            }
        });
    }

    updateUI();
})();
</script>

@endsection