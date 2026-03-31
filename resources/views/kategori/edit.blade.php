@extends('layout.template')
@section('title', 'Edit Kategori')
@section('content')

<style>
    .edit-kat-wrap { font-family: 'DM Sans', 'Segoe UI', sans-serif; max-width: 520px; }

    .edit-kat-card {
        background: #FFFFFF;
        border: 1.5px solid #DBEAFE;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(37,99,235,0.06);
    }

    /* ── Header ── */
    .edit-kat-header {
        background: #EFF6FF;
        border-bottom: 1.5px solid #DBEAFE;
        padding: 20px 26px;
        display: flex; align-items: center; gap: 12px;
    }
    .edit-kat-header-icon {
        width: 40px; height: 40px; background: #FFFFFF;
        border: 1.5px solid #DBEAFE; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #3B82F6; font-size: 1rem; flex-shrink: 0;
    }
    .edit-kat-header-title { font-size: 0.97rem; font-weight: 700; color: #0F172A; margin: 0; }
    .edit-kat-header-sub   { font-size: 0.75rem; color: #94A3B8; margin: 0; }

    /* ── Body ── */
    .edit-kat-body { padding: 26px; }

    .mfield { margin-bottom: 20px; }
    .mfield-label {
        display: block; font-size: 0.68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.6px;
        color: #64748B; margin-bottom: 7px;
    }
    .mfield-input {
        width: 100%; padding: 11px 14px;
        border: 1.5px solid #DBEAFE; border-radius: 10px;
        font-size: 0.87rem; color: #0F172A;
        background: #FFFFFF; outline: none; font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .mfield-input:focus {
        border-color: #60A5FA;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.15);
    }
    .mfield-input.is-invalid { border-color: #EF4444 !important; }
    .invalid-feedback { font-size: 0.72rem; color: #EF4444; margin-top: 4px; display: block; }

    /* ── Actions ── */
    .edit-kat-actions {
        display: flex; gap: 9px;
        padding: 18px 26px;
        border-top: 1px solid #F1F5F9;
        background: #FAFCFF;
    }
    .btn-simpan {
        flex: 1;
        display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        padding: 11px; border-radius: 11px;
        font-size: 0.88rem; font-weight: 700;
        background: #2563EB !important; color: #ffffff !important;
        border: none; cursor: pointer; font-family: inherit;
        box-shadow: 0 3px 10px rgba(37,99,235,0.25);
        transition: background 0.2s, transform 0.15s;
    }
    .btn-simpan:hover { background: #1D4ED8 !important; transform: translateY(-1px); }

    .btn-batal {
        display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        padding: 11px 22px; border-radius: 11px;
        font-size: 0.88rem; font-weight: 600;
        background: #F1F5F9 !important; color: #64748B !important;
        border: none; cursor: pointer; font-family: inherit;
        text-decoration: none;
        transition: background 0.15s;
    }
    .btn-batal:hover { background: #E2E8F0 !important; color: #334155 !important; }
</style>

<div class="edit-kat-wrap">
    <div class="edit-kat-card">

        {{-- ── Header ── --}}
        <div class="edit-kat-header">
            <div class="edit-kat-header-icon"><i class="fas fa-pen"></i></div>
            <div>
                <p class="edit-kat-header-title">Edit Kategori</p>
                <p class="edit-kat-header-sub">Ubah nama kategori yang sudah ada</p>
            </div>
        </div>

        {{-- ── Form ── --}}
        <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="edit-kat-body">
                <div class="mfield">
                    <label class="mfield-label">
                        <i class="fas fa-tag" style="margin-right:4px;"></i>Nama Kategori
                    </label>
                    <input type="text"
                           name="nama_kategori"
                           class="mfield-input @error('nama_kategori') is-invalid @enderror"
                           value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                           placeholder="Masukkan nama kategori...">
                    @error('nama_kategori')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="edit-kat-actions">
                <button type="submit" class="btn-simpan">
                    <i class="fas fa-check"></i> Simpan Perubahan
                </button>
                <a href="{{ route('kategori.index') }}" class="btn-batal">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>

        </form>
    </div>
</div>

@endsection