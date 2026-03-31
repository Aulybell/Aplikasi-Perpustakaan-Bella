@extends('layout.template')
@section('title', 'Form Peminjaman Multiple - ReadMe')
@section('content')

<style>
    :root {
        --blue-50:  #EFF6FF;
        --blue-100: #DBEAFE;
        --blue-200: #BFDBFE;
        --blue-300: #93C5FD;
        --blue-400: #60A5FA;
        --blue-500: #3B82F6;
        --blue-600: #2563EB;
        --blue-700: #1D4ED8;
        --white:    #FFFFFF;
        --gray-50:  #F8FAFC;
        --gray-100: #F1F5F9;
        --gray-200: #E2E8F0;
        --gray-300: #CBD5E1;
        --gray-400: #94A3B8;
        --gray-500: #64748B;
        --gray-700: #334155;
        --gray-900: #0F172A;
        --green-50: #F0FDF4;
        --green-100:#DCFCE7;
        --green-600:#16A34A;
        --red-50:   #FFF1F2;
        --red-100:  #FFE4E6;
        --red-500:  #EF4444;
    }

    .pinjam-page {
        font-family: 'DM Sans', 'Segoe UI', sans-serif;
        min-height: 100vh;
        background: linear-gradient(160deg, var(--blue-50) 0%, var(--white) 60%);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 48px 20px 60px;
    }

    /* ── Card ── */
    .pinjam-card {
        width: 100%;
        max-width: 520px;
        background: var(--white);
        border: 1.5px solid var(--blue-100);
        border-radius: 24px;
        box-shadow: 0 8px 40px rgba(37,99,235,0.09);
        overflow: hidden;
    }

    /* ── Card Header ── */
    .pinjam-card-header {
        background: var(--blue-50);
        border-bottom: 1.5px solid var(--blue-100);
        padding: 28px 32px 24px;
        text-align: center;
    }
    .pinjam-card-header-icon {
        width: 52px; height: 52px;
        background: var(--white);
        border: 1.5px solid var(--blue-100);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px;
        color: var(--blue-500);
        font-size: 1.3rem;
        box-shadow: 0 2px 8px rgba(37,99,235,0.08);
    }
    .pinjam-card-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.4rem; font-weight: 700;
        color: var(--gray-900); margin: 0 0 4px;
    }
    .pinjam-card-sub { font-size: 0.8rem; color: var(--gray-400); margin: 0; }

    /* ── Card Body ── */
    .pinjam-card-body { padding: 28px 32px 32px; }

    /* ── Alerts ── */
    .pinjam-alert {
        display: flex; align-items: flex-start; gap: 10px;
        padding: 12px 16px; border-radius: 12px;
        font-size: 0.85rem; font-weight: 500; margin-bottom: 20px;
    }
    .pinjam-alert.success { background: var(--green-50); color: var(--green-600); border: 1px solid var(--green-100); }
    .pinjam-alert.danger  { background: var(--red-50);   color: var(--red-500);   border: 1px solid var(--red-100); }
    .pinjam-alert i { margin-top: 1px; flex-shrink: 0; }
    .pinjam-alert ul { margin: 0; padding-left: 16px; }
    .pinjam-alert ul li { margin-bottom: 2px; }

    /* ── Section label ── */
    .form-section-label {
        font-size: 0.68rem; font-weight: 700;
        letter-spacing: 0.8px; text-transform: uppercase;
        color: var(--blue-500); margin-bottom: 14px;
        padding-bottom: 8px; border-bottom: 1px solid var(--blue-100);
    }

    /* ── Buku List (multiple) ── */
    .buku-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 24px; }
    .buku-item {
        background: var(--blue-50); border: 1.5px solid var(--blue-100);
        border-radius: 12px; padding: 12px 16px;
        display: flex; align-items: center; gap: 12px;
    }
    .buku-cover {
        width: 40px; height: 54px; object-fit: cover;
        border-radius: 6px; flex-shrink: 0;
        box-shadow: 0 1px 6px rgba(15,23,42,.10);
    }
    .buku-item-info { flex: 1; min-width: 0; }
    .buku-item-kategori {
        display: inline-block; font-size: .62rem; font-weight: 700;
        text-transform: uppercase; color: var(--blue-600);
        background: var(--white); border: 1px solid var(--blue-200);
        padding: 1px 6px; border-radius: 3px; margin-bottom: 3px;
    }
    .buku-item-title {
        font-size: 0.85rem; font-weight: 700; color: var(--gray-900);
        margin: 0 0 2px; line-height: 1.3;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .buku-item-author { font-size: 0.73rem; color: var(--gray-400); margin: 0; }

    /* ── Form fields ── */
    .field-wrap { margin-bottom: 18px; }
    .field-label {
        display: block; font-size: 0.78rem; font-weight: 700;
        color: var(--gray-700); margin-bottom: 7px; letter-spacing: 0.2px;
    }
    .field-input {
        width: 100%; padding: 12px 16px;
        border: 1.5px solid var(--blue-100); border-radius: 12px;
        font-size: 0.88rem; color: var(--gray-900);
        background: var(--white); outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: inherit;
    }
    .field-input::placeholder { color: var(--gray-300); }
    .field-input:focus {
        border-color: var(--blue-400);
        box-shadow: 0 0 0 3px rgba(96,165,250,0.15);
    }
    .field-input.is-invalid { border-color: var(--red-500); }
    .field-input.readonly {
        background: var(--gray-50); color: var(--gray-500); cursor: not-allowed;
    }
    .field-icon-wrap { position: relative; }
    .field-icon-wrap .lock-icon {
        position: absolute; right: 14px; top: 50%;
        transform: translateY(-50%);
        color: var(--gray-300); font-size: 0.78rem; pointer-events: none;
    }

    /* ── Info note ── */
    .info-note {
        display: flex; align-items: center; gap: 8px;
        background: var(--blue-50); border: 1px solid var(--blue-100);
        border-radius: 10px; padding: 10px 14px;
        font-size: 0.77rem; color: var(--blue-600); font-weight: 500;
        margin-top: 18px;
    }
    .info-note i { color: var(--blue-400); flex-shrink: 0; }

    /* ── Buttons ── */
    .btn-submit {
        width: 100%; padding: 14px;
        background: #2563EB; color: #fff; border: none;
        border-radius: 14px; font-size: 0.92rem; font-weight: 700;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: background 0.2s, transform 0.15s, box-shadow 0.15s;
        box-shadow: 0 4px 14px rgba(37,99,235,0.3);
        margin-bottom: 10px; font-family: inherit;
    }
    .btn-submit:hover { background: #1D4ED8; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,99,235,0.35); }
    .btn-back {
        width: 100%; padding: 12px;
        background: var(--white); color: var(--gray-500);
        border: 1.5px solid var(--gray-200); border-radius: 14px;
        font-size: 0.88rem; font-weight: 600; cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
        font-family: inherit;
    }
    .btn-back:hover { background: var(--gray-50); border-color: var(--gray-300); color: var(--gray-700); }

    @media (max-width: 560px) {
        .pinjam-card-body { padding: 18px 16px 24px; }
        .pinjam-card-header { padding: 20px 16px 16px; }
    }
</style>

<div class="pinjam-page">
    <div class="pinjam-card">

        {{-- Header (sama persis dengan create biasa) --}}
        <div class="pinjam-card-header">
            <div class="pinjam-card-header-icon">
                <i class="fas fa-bookmark"></i>
            </div>
            <h2 class="pinjam-card-title">Form Peminjaman</h2>
            <p class="pinjam-card-sub">Isi data di bawah untuk meminjam buku</p>
        </div>

        <div class="pinjam-card-body">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="pinjam-alert success">
                    <i class="fas fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="pinjam-alert danger">
                    <i class="fas fa-circle-exclamation"></i>
                    <span>{!! session('error') !!}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="pinjam-alert danger">
                    <i class="fas fa-circle-exclamation"></i>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Daftar buku (mirip buku preview di create biasa, tapi list) --}}
            <p class="form-section-label">
                <i class="fas fa-books" style="margin-right:5px;"></i>
                {{ $bukus->count() }} Buku yang Dipinjam
            </p>
            <div class="buku-list">
                @foreach($bukus as $buku)
                    @php
                        $foto = $buku->foto
                            ? asset('storage/' . $buku->foto)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($buku->judul)
                              . '&background=2563eb&color=fff&size=200&bold=true&font-size=0.3';
                    @endphp
                    <div class="buku-item">
                        <img class="buku-cover" src="{{ $foto }}" alt="{{ $buku->judul }}"
                            onerror="this.src='https://ui-avatars.com/api/?name=B&background=bfdbfe&color=2563eb&size=200'">
                        <div class="buku-item-info">
                            @if($buku->kategori)
                                <span class="buku-item-kategori">{{ $buku->kategori->nama_kategori ?? 'Umum' }}</span>
                            @endif
                            <p class="buku-item-title">{{ $buku->judul }}</p>
                            <p class="buku-item-author">oleh {{ $buku->pengarang }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Form — logika SAMA dengan create biasa --}}
            <p class="form-section-label">
                <i class="fas fa-user" style="margin-right:5px;"></i>
                Data Peminjam
            </p>

            <form action="{{ route('pinjam.store.multiple') }}" method="POST" id="formKonfirmasi">
                @csrf

                {{-- Hidden: teruskan buku_ids ke storeMultiple --}}
                @foreach($bukus as $buku)
                    <input type="hidden" name="buku_ids[]" value="{{ $buku->id }}">
                @endforeach

                {{-- Nama (readonly, auto-fill) --}}
                <div class="field-wrap">
                    <label class="field-label" for="nama_peminjam">Nama Peminjam</label>
                    <div class="field-icon-wrap">
                        <input type="text"
                            id="nama_peminjam"
                            name="nama_peminjam"
                            class="field-input readonly"
                            value="{{ old('nama_peminjam', auth()->user()->name ?? '') }}"
                            readonly
                            style="padding-right: 38px;">
                        <i class="fas fa-lock lock-icon"></i>
                    </div>
                    
                    <p style="font-size:.72rem;color:var(--gray-400);margin-top:5px;display:flex;align-items:center;gap:4px;">
                        <i class="fas fa-circle-info" style="font-size:.65rem;color:#93c5fd;"></i>
                        
                    </p>
                </div>

                {{-- No Telepon --}}
                <div class="field-wrap">
                    <label class="field-label" for="no_telp">No. Telepon</label>
                    <input type="text"
                        id="no_telp"
                        name="no_telp"
                        class="field-input {{ $errors->has('no_telp') ? 'is-invalid' : '' }}"
                        placeholder="08xxxxxxxxxx"
                        value="{{ old('no_telp') }}"
                        maxlength="20"
                        required>
                </div>

                {{-- Tanggal Pinjam — SAMA dengan create biasa --}}
                <div class="field-wrap">
                    <label class="field-label" for="tanggal_pinjam">Tanggal Pinjam</label>
                    <input type="date"
                        id="tanggal_pinjam"
                        name="tanggal_pinjam"
                        class="field-input {{ $errors->has('tanggal_pinjam') ? 'is-invalid' : '' }}"
                        value="{{ old('tanggal_pinjam') }}"
                        min="{{ now()->format('Y-m-d') }}"
                        required>
                </div>


                {{-- Estimasi Kembali (hanya tampil jika status == 'dipinjam') --}}
                @if(isset($status) && $status === 'dipinjam')
                    <div class="field-wrap">
                        <label class="field-label">Perkiraan Tanggal Kembali</label>
                        <input type="text" class="field-input readonly" value="{{ isset($tanggal_pinjam) ? \Carbon\Carbon::parse($tanggal_pinjam)->addDays(14)->format('d M Y') : '-' }}" readonly>
                    </div>
                @endif
                <input type="hidden" id="tanggal_kembali_hidden" name="tanggal_kembali" value="">

                <div style="margin-top: 24px;">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-bookmark"></i> Simpan Peminjaman
                    </button>
                    <a href="{{ route('keranjang.index') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Script SAMA dengan create biasa --}}
<script>
document.getElementById('tanggal_pinjam').addEventListener('change', function () {
    const tanggalPinjam = new Date(this.value);
    if (tanggalPinjam) {
        const tanggalKembali = new Date(tanggalPinjam);
        tanggalKembali.setDate(tanggalPinjam.getDate() + 14);
        const formatted = tanggalKembali.toISOString().split('T')[0];
        const el = document.getElementById('tanggal_kembali_hidden');
        if (el) el.value = formatted;
    }
});
</script>

@endsection