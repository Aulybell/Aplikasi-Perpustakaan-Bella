@extends('layout.template')
@section('title', 'Form Peminjaman - ReadMe')
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
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0 0 4px;
    }
    .pinjam-card-sub {
        font-size: 0.8rem;
        color: var(--gray-400);
        margin: 0;
    }

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

    /* ── Buku Preview ── */
    .buku-preview {
        background: var(--blue-50);
        border: 1.5px solid var(--blue-100);
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .buku-preview-icon {
        width: 38px; height: 38px;
        background: var(--white);
        border: 1px solid var(--blue-100);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: var(--blue-500);
        font-size: 0.95rem;
        flex-shrink: 0;
    }
    .buku-preview-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0 0 2px;
        line-height: 1.3;
    }
    .buku-preview-author {
        font-size: 0.76rem;
        color: var(--gray-400);
        margin: 0;
    }

    /* ── Section label ── */
    .form-section-label {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: var(--blue-500);
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--blue-100);
    }

    /* ── Form fields ── */
    .field-wrap { margin-bottom: 18px; }
    .field-label {
        display: block;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--gray-700);
        margin-bottom: 7px;
        letter-spacing: 0.2px;
    }
    .field-input {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid var(--blue-100);
        border-radius: 12px;
        font-size: 0.88rem;
        color: var(--gray-900);
        background: var(--white);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: inherit;
    }
    .field-input::placeholder { color: var(--gray-300); }
    .field-input:focus {
        border-color: var(--blue-400);
        box-shadow: 0 0 0 3px rgba(96,165,250,0.15);
    }
    .field-input.is-invalid { border-color: var(--red-500); }

    /* ── Buttons ── */
    .btn-submit {
        width: 100%;
        padding: 14px;
        background: #2563EB;
        color: #ffffff;
        border: none;
        border-radius: 14px;
        font-size: 0.92rem;
        font-weight: 700;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: background 0.2s, transform 0.15s, box-shadow 0.15s;
        box-shadow: 0 4px 14px rgba(37,99,235,0.3);
        margin-bottom: 10px;
        font-family: inherit;
    }
    .btn-submit:hover {
        background: #1D4ED8;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(37,99,235,0.35);
    }

    .btn-back {
        width: 100%;
        padding: 12px;
        background: var(--white);
        color: var(--gray-500);
        border: 1.5px solid var(--gray-200);
        border-radius: 14px;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        text-decoration: none;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
        font-family: inherit;
    }
    .btn-back:hover {
        background: var(--gray-50);
        border-color: var(--gray-300);
        color: var(--gray-700);
    }

    /* ── Info note ── */
    .info-note {
        display: flex; align-items: center; gap: 8px;
        background: var(--blue-50);
        border: 1px solid var(--blue-100);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.77rem;
        color: var(--blue-600);
        font-weight: 500;
        margin-top: 18px;
    }
    .info-note i { color: var(--blue-400); flex-shrink: 0; }
</style>

<div class="pinjam-page">
    <div class="pinjam-card">

        {{-- ── Header ── --}}
        <div class="pinjam-card-header">
            <div class="pinjam-card-header-icon">
                <i class="fas fa-bookmark"></i>
            </div>
            <h2 class="pinjam-card-title">Form Peminjaman</h2>
            <p class="pinjam-card-sub">Isi data di bawah untuk meminjam buku</p>
        </div>

        <div class="pinjam-card-body">

            {{-- ── Alerts ── --}}
            @if(session()->has('success'))
                <div class="pinjam-alert success">
                    <i class="fas fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session()->has('error'))
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

            {{-- ── Buku Preview ── --}}
            <p class="form-section-label">Buku yang Dipinjam</p>
            <div class="buku-preview">
                <div class="buku-preview-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <p class="buku-preview-title">{{ $buku->judul }}</p>
                    <p class="buku-preview-author">oleh {{ $buku->pengarang }}</p>
                </div>
            </div>

            {{-- ── Form ── --}}
            <p class="form-section-label">Data Peminjam</p>

            <form action="{{ route('pinjam.store') }}" method="POST">
                @csrf
                <input type="hidden" name="buku_id" value="{{ $buku->id }}">

                <div class="field-wrap">
                    <label class="field-label" for="nama_peminjam">Nama Peminjam</label>
                    <input type="text"
                        id="nama_peminjam"
                        name="nama_peminjam"
                        class="field-input {{ $errors->has('nama_peminjam') ? 'is-invalid' : '' }}"
                        placeholder="Masukkan nama lengkap"
                         value="{{ old('nama_peminjam', auth()->user()->name ?? '') }}"
                        required>
                </div>

                <div class="field-wrap">
                    <label class="field-label" for="no_telp">No. Telepon</label>
                    <input type="text"
                        id="no_telp"
                        name="no_telp"
                        class="field-input {{ $errors->has('no_telp') ? 'is-invalid' : '' }}"
                        placeholder="08xxxxxxxxxx"
                        value="{{ old('no_telp') }}"
                        required>
                </div>

                <div class="field-wrap">
                    <label class="field-label" for="tanggal_pinjam">Tanggal Pinjam</label>
                    <input type="date"
                        id="tanggal_pinjam"
                        name="tanggal_pinjam"
                        class="field-input {{ $errors->has('tanggal_pinjam') ? 'is-invalid' : '' }}"
                        value="{{ old('tanggal_pinjam') }}"
                        required>
                </div>

                <div class="info-note">
                    <i class="fas fa-circle-info"></i>
                    Estimasi pengembalian otomatis dihitung 14 hari dari tanggal pinjam.
                </div>

                <div style="margin-top: 24px;">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-bookmark"></i> Simpan Peminjaman
                    </button>
                    <a href="{{ route('koleksi') }}" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Kembali ke Koleksi
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
document.getElementById('tanggal_pinjam').addEventListener('change', function () {
    const tanggalPinjam = new Date(this.value);
    if (tanggalPinjam) {
        const tanggalKembali = new Date(tanggalPinjam);
        tanggalKembali.setDate(tanggalPinjam.getDate() + 14);
        const formatted = tanggalKembali.toISOString().split('T')[0];
        const el = document.getElementById('tanggal_kembali');
        if (el) el.value = formatted;
    }
});
</script>

@endsection