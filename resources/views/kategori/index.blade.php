@extends('layout.template')
@section('title', 'Kategori')
@section('content')

<style>
    .kat-wrap { font-family: 'DM Sans', 'Segoe UI', sans-serif; }

    /* ── Alert ── */
    .kat-alert {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 18px; border-radius: 12px;
        font-size: 0.85rem; font-weight: 500; margin-bottom: 20px;
        background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7;
    }

    /* ── Card ── */
    .kat-card {
        background: #FFFFFF;
        border: 1.5px solid #DBEAFE;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(37,99,235,0.06);
    }

    /* ── Header ── */
    .kat-header {
        background: #EFF6FF;
        border-bottom: 1.5px solid #DBEAFE;
        padding: 20px 26px;
        display: flex; align-items: center; gap: 12px;
    }
    .kat-header-icon {
        width: 40px; height: 40px; background: #FFFFFF;
        border: 1.5px solid #DBEAFE; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #3B82F6; font-size: 1rem; flex-shrink: 0;
    }
    .kat-header-title { font-size: 0.97rem; font-weight: 700; color: #0F172A; margin: 0; }
    .kat-header-sub   { font-size: 0.75rem; color: #94A3B8; margin: 0; }

    /* ── Add Form ── */
    .kat-form-section {
        padding: 20px 26px;
        border-bottom: 1.5px solid #F1F5F9;
        background: #FAFCFF;
    }
    .kat-form-label {
        font-size: 0.68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.6px;
        color: #64748B; margin-bottom: 8px; display: block;
    }
    .kat-form-row {
        display: flex; gap: 10px; align-items: flex-start; flex-wrap: wrap;
    }
    .kat-input-wrap { flex: 1; min-width: 200px; }
    .kat-input {
        width: 100%; padding: 10px 14px;
        border: 1.5px solid #DBEAFE; border-radius: 10px;
        font-size: 0.87rem; color: #0F172A;
        background: #FFFFFF; outline: none; font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .kat-input:focus {
        border-color: #60A5FA;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.15);
    }
    .kat-input::placeholder { color: #CBD5E1; }
    .kat-input.is-invalid { border-color: #EF4444 !important; }
    .invalid-feedback { font-size: 0.72rem; color: #EF4444; margin-top: 4px; }

    .btn-tambah {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; border-radius: 10px;
        font-size: 0.84rem; font-weight: 700;
        background: #2563EB !important; color: #ffffff !important;
        border: none; cursor: pointer; font-family: inherit;
        box-shadow: 0 3px 10px rgba(37,99,235,0.25);
        transition: background 0.2s, transform 0.15s;
        white-space: nowrap;
    }
    .btn-tambah:hover { background: #1D4ED8 !important; transform: translateY(-1px); }

    /* ── Table ── */
    .kat-table-wrap { overflow-x: auto; }
    .kat-table { width: 100%; border-collapse: collapse; font-size: 0.845rem; }
    .kat-table thead tr { background: #F8FAFC; border-bottom: 1.5px solid #DBEAFE; }
    .kat-table thead th {
        padding: 13px 18px; font-size: 0.7rem; font-weight: 700;
        letter-spacing: 0.8px; text-transform: uppercase; color: #3B82F6; white-space: nowrap;
    }
    .kat-table tbody tr { border-bottom: 1px solid #F1F5F9; transition: background 0.15s; }
    .kat-table tbody tr:last-child { border-bottom: none; }
    .kat-table tbody tr:hover { background: #EFF6FF; }
    .kat-table tbody td { padding: 14px 18px; color: #334155; vertical-align: middle; }

    .td-no { font-size: 0.74rem; font-weight: 700; color: #CBD5E1; text-align: center; width: 48px; }

    /* Kategori name with pill icon */
    .kat-name-cell { display: flex; align-items: center; gap: 10px; }
    .kat-icon-box {
        width: 32px; height: 32px; border-radius: 9px;
        background: #EFF6FF; border: 1.5px solid #DBEAFE;
        display: flex; align-items: center; justify-content: center;
        color: #3B82F6; font-size: 0.78rem; flex-shrink: 0;
    }
    .kat-name { font-weight: 600; color: #0F172A; }

    /* Action buttons */
    .act-wrap { display: flex; gap: 6px; }
    .btn-act {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 12px; border-radius: 8px;
        font-size: 0.75rem; font-weight: 600;
        border: none; cursor: pointer; font-family: inherit;
        transition: opacity 0.15s, transform 0.12s; white-space: nowrap;
        text-decoration: none;
    }
    .btn-act:hover { opacity: 0.85; transform: translateY(-1px); }
    .btn-act.edit   { background: #EFF6FF !important; color: #2563EB !important; border: 1.5px solid #DBEAFE; }
    .btn-act.delete { background: #EF4444 !important; color: #ffffff !important; }

    /* ── Pagination ── */
    .kat-footer { padding: 16px 22px; border-top: 1px solid #F1F5F9; }
    .kat-wrap .pagination { margin: 0; gap: 4px; justify-content: center; }
    .kat-wrap .page-link {
        border-radius: 8px !important; border: 1.5px solid #DBEAFE;
        color: #2563EB; font-size: 0.82rem; font-weight: 600;
        padding: 6px 12px; transition: all 0.15s;
    }
    .kat-wrap .page-link:hover { background: #EFF6FF; border-color: #93C5FD; }
    .kat-wrap .page-item.active .page-link {
        background: #2563EB !important; border-color: #2563EB !important;
        color: white !important; box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    }
    .kat-wrap .page-item.disabled .page-link {
        color: #CBD5E1; border-color: #F1F5F9; background: #F8FAFC;
    }

    /* ── Empty ── */
    .kat-empty { padding: 52px 20px; text-align: center; }
    .kat-empty-icon {
        width: 60px; height: 60px; background: #EFF6FF; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px; font-size: 1.4rem; color: #BFDBFE;
    }
    .kat-empty p { color: #94A3B8; font-size: 0.88rem; margin: 0; }
</style>

<div class="kat-wrap">

    {{-- ── Alert ── --}}
    @if(session('success'))
        <div class="kat-alert">
            <i class="fas fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="kat-card">

        {{-- ── Header ── --}}
        <div class="kat-header">
            <div class="kat-header-icon"><i class="fas fa-tags"></i></div>
            <div>
                <p class="kat-header-title">Daftar Kategori</p>
                <p class="kat-header-sub">Kelola kategori koleksi buku perpustakaan</p>
            </div>
        </div>

        {{-- ── Add Form ── --}}
        <div class="kat-form-section">
            <label class="kat-form-label">
                <i class="fas fa-plus" style="margin-right:4px;"></i>Tambah Kategori Baru
            </label>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="kat-form-row">
                    <div class="kat-input-wrap">
                        <input type="text"
                               name="nama_kategori"
                               class="kat-input @error('nama_kategori') is-invalid @enderror"
                               placeholder="Masukkan nama kategori..."
                               value="{{ old('nama_kategori') }}">
                        @error('nama_kategori')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn-tambah">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Table ── --}}
        <div class="kat-table-wrap">
            <table class="kat-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">#</th>
                        <th>Nama Kategori</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $index => $k)
                    <tr>
                        <td class="td-no">{{ $kategoris->firstItem() + $index }}</td>
                        <td>
                            <div class="kat-name-cell">
                                <div class="kat-icon-box">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <span class="kat-name">{{ $k->nama_kategori }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="act-wrap">
                                <a href="{{ route('kategori.edit', $k->id) }}" class="btn-act edit">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                <form action="{{ route('kategori.destroy', $k->id) }}" method="POST"
                                      style="display:inline-block"
                                      onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-act delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">
                            <div class="kat-empty">
                                <div class="kat-empty-icon"><i class="fas fa-tags"></i></div>
                                <p>Belum ada kategori. Tambahkan kategori pertama!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        <div class="kat-footer">
            {{ $kategoris->links() }}
        </div>

    </div>
</div>

@endsection