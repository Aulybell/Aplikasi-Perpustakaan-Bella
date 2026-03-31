<?php

use App\Models\Buku;
use App\Models\Kategori;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\withoutUrlPagination;

new class extends Component
{
    use WithPagination, withoutUrlPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $addPage, $editPage = false;
    public $judul, $pengarang, $penerbit, $tahun_terbit, $kategori_id, $id, $foto, $stok, $sinopsis;

    public function getBukusProperty()
    {
        return Buku::paginate(10);
    }

    public function create() { $this->addPage = true; }

    public function store()
    {
        $this->validate([
            'judul'        => 'required',
            'pengarang'    => 'required',
            'penerbit'     => 'required',
            'tahun_terbit' => 'required',
            'kategori_id'  => 'required',
            'foto'         => 'required|image',
            'stok'         => 'required',
            'sinopsis'     => 'required',
        ],[
            'judul.required'        => 'Judul wajib diisi.',
            'pengarang.required'    => 'Pengarang wajib diisi.',
            'penerbit.required'     => 'Penerbit wajib diisi.',
            'tahun_terbit.required' => 'Tahun Terbit wajib diisi.',
            'kategori.required'     => 'Kategori wajib diisi.',
            'foto.required'         => 'Foto wajib diisi.',
            'stok.required'         => 'Stok wajib diisi.',
            'sinopsis.required'     => 'Sinopsis wajib diisi.',
        ]);

        $path = $this->foto->store('buku', 'public');
        Buku::create([
            'user_id'      => auth()->id(),
            'judul'        => $this->judul,
            'pengarang'    => $this->pengarang,
            'penerbit'     => $this->penerbit,
            'tahun_terbit' => $this->tahun_terbit,
            'kategori_id'  => $this->kategori_id,
            'kategori'     => Kategori::find($this->kategori_id)?->nama_kategori,
            'foto'         => $path,
            'stok'         => $this->stok,
            'sinopsis'     => $this->sinopsis,
        ]);

        session()->flash('message', 'Buku berhasil ditambahkan.');
        $this->addPage = false;
        $this->reset(['judul','pengarang','penerbit','tahun_terbit','kategori_id','foto','stok','sinopsis']);
    }

    public function edit($id)
    {
        $this->editPage = true;
        $this->id       = $id;
        $buku = Buku::find($id);
        $this->judul        = $buku->judul;
        $this->pengarang    = $buku->pengarang;
        $this->penerbit     = $buku->penerbit;
        $this->tahun_terbit = $buku->tahun_terbit;
        $this->kategori_id  = $buku->kategori_id ?? null;
        $this->stok         = $buku->stok;
        $this->sinopsis     = $buku->sinopsis;
    }

    public function update()
    {
        $buku = Buku::find($this->id);
        $data = [
            'user_id'      => auth()->id(),
            'judul'        => $this->judul,
            'pengarang'    => $this->pengarang,
            'penerbit'     => $this->penerbit,
            'tahun_terbit' => $this->tahun_terbit,
            'kategori_id'  => $this->kategori_id,
            'kategori'     => Kategori::find($this->kategori_id)?->nama_kategori,
            'stok'         => $this->stok,
            'sinopsis'     => $this->sinopsis,
        ];

        if (!empty($this->foto)) {
            @unlink(storage_path('app/public/' . $buku->foto));
            $data['foto'] = $this->foto->store('buku', 'public');
        } else {
            $data['foto'] = $buku->foto;
        }

        $buku->update($data);
        session()->flash('message', 'Buku berhasil diupdate.');
        $this->reset();
    }

    public function destroy($id)
    {
        $buku = Buku::find($id);
        @unlink(storage_path('app/public/' . $buku->foto));
        $buku->delete();
        session()->flash('message', 'Buku berhasil dihapus.');
        $this->reset();
    }
};
?>

<style>
    .buku-wrap { font-family: 'DM Sans', 'Segoe UI', sans-serif; }

    /* ── Alert ── */
    .buku-alert {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 18px; border-radius: 12px;
        font-size: 0.85rem; font-weight: 500; margin-bottom: 20px;
        background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7;
    }

    /* ── Card ── */
    .buku-card {
        background: #FFFFFF;
        border: 1.5px solid #DBEAFE;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(37,99,235,0.06);
    }

    /* ── Header ── */
    .buku-header {
        background: #EFF6FF;
        border-bottom: 1.5px solid #DBEAFE;
        padding: 20px 26px;
        display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }
    .buku-header-left { display: flex; align-items: center; gap: 12px; }
    .buku-header-icon {
        width: 40px; height: 40px; background: #FFFFFF;
        border: 1.5px solid #DBEAFE; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #3B82F6; font-size: 1rem; flex-shrink: 0;
    }
    .buku-header-title { font-size: 0.97rem; font-weight: 700; color: #0F172A; margin: 0; }
    .buku-header-sub   { font-size: 0.75rem; color: #94A3B8; margin: 0; }

    .btn-tambah {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 11px;
        font-size: 0.82rem; font-weight: 700;
        background: #2563EB !important; color: #ffffff !important;
        border: none; cursor: pointer; font-family: inherit;
        box-shadow: 0 3px 10px rgba(37,99,235,0.25);
        transition: background 0.2s, transform 0.15s;
    }
    .btn-tambah:hover { background: #1D4ED8 !important; transform: translateY(-1px); }

    /* ── Table ── */
    .buku-table-wrap { overflow-x: auto; }
    .buku-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
    .buku-table thead tr { background: #F8FAFC; border-bottom: 1.5px solid #DBEAFE; }
    .buku-table thead th {
        padding: 13px 16px; font-size: 0.7rem; font-weight: 700;
        letter-spacing: 0.8px; text-transform: uppercase; color: #3B82F6; white-space: nowrap;
    }
    .buku-table tbody tr { border-bottom: 1px solid #F1F5F9; transition: background 0.15s; }
    .buku-table tbody tr:last-child { border-bottom: none; }
    .buku-table tbody tr:hover { background: #EFF6FF; }
    .buku-table tbody td { padding: 13px 16px; color: #334155; vertical-align: middle; }

    .td-no { font-size: 0.74rem; font-weight: 700; color: #CBD5E1; text-align: center; width: 36px; }
    .td-judul { font-weight: 700; color: #0F172A; max-width: 160px; white-space: normal; line-height: 1.4; }
    .td-meta  { color: #64748B; font-size: 0.82rem; }
    .td-sinopsis {
        color: #94A3B8; font-size: 0.8rem;
        max-width: 180px; white-space: nowrap;
        overflow: hidden; text-overflow: ellipsis;
    }

    /* Book thumbnail */
    .book-thumb {
        width: 48px; height: 66px; border-radius: 8px; object-fit: cover;
        box-shadow: 0 2px 8px rgba(37,99,235,0.12);
        background: #DBEAFE;
    }
    .book-thumb-placeholder {
        width: 48px; height: 66px; border-radius: 8px;
        background: #EFF6FF; border: 1.5px solid #DBEAFE;
        display: flex; align-items: center; justify-content: center;
        color: #93C5FD; font-size: 1.1rem;
    }

    /* Stok badge */
    .stok-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 100px;
        font-size: 0.72rem; font-weight: 700;
    }
    .stok-badge.ada   { background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7; }
    .stok-badge.habis { background: #FFF1F2; color: #EF4444; border: 1px solid #FFE4E6; }

    /* Kategori pill */
    .kat-pill {
        display: inline-flex; align-items: center;
        background: #EFF6FF; color: #2563EB;
        border: 1px solid #DBEAFE;
        font-size: 0.7rem; font-weight: 600;
        padding: 2px 9px; border-radius: 100px;
    }

    /* Action buttons */
    .act-wrap { display: flex; gap: 6px; }
    .btn-act {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 12px; border-radius: 8px;
        font-size: 0.75rem; font-weight: 600;
        border: none; cursor: pointer; font-family: inherit;
        transition: opacity 0.15s, transform 0.12s; white-space: nowrap;
    }
    .btn-act:hover { opacity: 0.85; transform: translateY(-1px); }
    .btn-act.edit   { background: #EFF6FF !important; color: #2563EB !important; border: 1.5px solid #DBEAFE; }
    .btn-act.delete { background: #EF4444 !important; color: #ffffff !important; }

    /* ── Footer / Pagination ── */
    .buku-footer { padding: 16px 22px; border-top: 1px solid #F1F5F9; }
    .buku-wrap .pagination { margin: 0; gap: 4px; justify-content: center; }
    .buku-wrap .page-link {
        border-radius: 8px !important; border: 1.5px solid #DBEAFE;
        color: #2563EB; font-size: 0.82rem; font-weight: 600;
        padding: 6px 12px; transition: all 0.15s;
    }
    .buku-wrap .page-link:hover { background: #EFF6FF; border-color: #93C5FD; }
    .buku-wrap .page-item.active .page-link {
        background: #2563EB !important; border-color: #2563EB !important;
        color: white !important; box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    }
    .buku-wrap .page-item.disabled .page-link {
        color: #CBD5E1; border-color: #F1F5F9; background: #F8FAFC;
    }

    /* ── Empty ── */
    .buku-empty { padding: 52px 20px; text-align: center; }
    .buku-empty-icon {
        width: 60px; height: 60px; background: #EFF6FF; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px; font-size: 1.4rem; color: #BFDBFE;
    }
    .buku-empty p { color: #94A3B8; font-size: 0.88rem; margin: 0; }

    /* ══════════════════════════════
       ── Modal ──
    ══════════════════════════════ */
    .buku-modal-overlay {
        position: fixed; inset: 0;
        background: rgba(15,23,42,0.45);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999; padding: 20px;
        animation: fadeIn 0.2s ease;
    }
    @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

    .buku-modal {
        background: #FFFFFF;
        border: 1.5px solid #DBEAFE;
        border-radius: 20px;
        width: 100%; max-width: 560px;
        max-height: 90vh; overflow-y: auto;
        box-shadow: 0 20px 50px rgba(37,99,235,0.15);
        animation: popIn 0.22s cubic-bezier(0.34,1.56,0.64,1);
    }
    @keyframes popIn {
        from { transform: scale(0.93) translateY(10px); opacity:0; }
        to   { transform: scale(1) translateY(0); opacity:1; }
    }

    .buku-modal-header {
        background: #EFF6FF; border-bottom: 1.5px solid #DBEAFE;
        padding: 18px 24px; position: sticky; top: 0; z-index: 1;
        display: flex; align-items: center; justify-content: space-between;
    }
    .buku-modal-title { font-size: 0.95rem; font-weight: 700; color: #0F172A; margin: 0; }
    .buku-modal-close {
        width: 32px; height: 32px; background: #FFFFFF;
        border: 1.5px solid #DBEAFE; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #94A3B8; font-size: 0.85rem;
        transition: background 0.15s, color 0.15s;
    }
    .buku-modal-close:hover { background: #DBEAFE; color: #2563EB; }

    .buku-modal-body { padding: 22px 24px 26px; }

    /* Form grid */
    .mform-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 16px;
    }
    .mform-full { grid-column: 1 / -1; }

    .mfield { display: flex; flex-direction: column; gap: 5px; }
    .mfield-label {
        font-size: 0.68rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.6px; color: #64748B;
    }
    .mfield-input, .mfield-select, .mfield-textarea {
        padding: 10px 14px;
        border: 1.5px solid #DBEAFE; border-radius: 10px;
        font-size: 0.87rem; color: #0F172A;
        background: #FFFFFF; outline: none; font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .mfield-input:focus, .mfield-select:focus, .mfield-textarea:focus {
        border-color: #60A5FA;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.15);
    }
    .mfield-input::placeholder, .mfield-textarea::placeholder { color: #CBD5E1; }
    .mfield-textarea { resize: vertical; min-height: 90px; }

    /* File upload */
    .mfield-file-wrap {
        border: 2px dashed #DBEAFE; border-radius: 10px;
        padding: 14px 16px; text-align: center; cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
    }
    .mfield-file-wrap:hover { border-color: #60A5FA; background: #EFF6FF; }
    .mfield-file-wrap input[type=file] { display: none; }
    .mfield-file-icon { font-size: 1.3rem; color: #93C5FD; margin-bottom: 6px; }
    .mfield-file-text { font-size: 0.78rem; color: #64748B; }
    .mfield-file-text strong { color: #2563EB; }

    /* Preview thumb */
    .foto-preview {
        width: 60px; height: 84px; border-radius: 10px;
        object-fit: cover; border: 1.5px solid #DBEAFE;
        margin-top: 8px;
    }

    .err-msg { font-size: 0.72rem; color: #EF4444; margin-top: 2px; }
    .mfield-hint { font-size: 0.71rem; color: #94A3B8; margin-top: 2px; }

    .modal-actions { display: flex; gap: 8px; margin-top: 20px; }
    .btn-modal-save {
        flex: 1; padding: 11px;
        background: #2563EB !important; color: #ffffff !important;
        border: none; border-radius: 11px; font-size: 0.88rem; font-weight: 700;
        cursor: pointer; font-family: inherit;
        box-shadow: 0 3px 10px rgba(37,99,235,0.25);
        transition: background 0.2s, transform 0.15s;
        display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .btn-modal-save:hover { background: #1D4ED8 !important; transform: translateY(-1px); }
    .btn-modal-cancel {
        padding: 11px 20px; background: #F1F5F9 !important; color: #64748B !important;
        border: none; border-radius: 11px; font-size: 0.88rem; font-weight: 600;
        cursor: pointer; font-family: inherit; transition: background 0.15s;
    }
    .btn-modal-cancel:hover { background: #E2E8F0 !important; }
</style>

<div class="buku-wrap">

    {{-- ── Alert ── --}}
    @if(session()->has('message'))
        <div class="buku-alert">
            <i class="fas fa-circle-check"></i> {{ session('message') }}
        </div>
    @endif

    <div class="buku-card">

        {{-- ── Header ── --}}
        <div class="buku-header">
            <div class="buku-header-left">
                <div class="buku-header-icon"><i class="fas fa-book"></i></div>
                <div>
                    <p class="buku-header-title">Kelola Buku</p>
                    <p class="buku-header-sub">Tambah, edit, dan hapus data koleksi buku</p>
                </div>
            </div>
            <button wire:click="create" class="btn-tambah">
                <i class="fas fa-plus"></i> Tambah Buku
            </button>
        </div>

        {{-- ── Table ── --}}
        <div class="buku-table-wrap">
            <table class="buku-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">#</th>
                        <th>Cover</th>
                        <th>Judul</th>
                        <th>Pengarang</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Sinopsis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->bukus as $data)
                    <tr>
                        <td class="td-no">
                            {{ ($this->bukus->currentPage() - 1) * $this->bukus->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            @if($data->foto)
                                <img src="{{ asset('storage/' . $data->foto) }}"
                                     alt="{{ $data->judul }}" class="book-thumb">
                            @else
                                <div class="book-thumb-placeholder">
                                    <i class="fas fa-book"></i>
                                </div>
                            @endif
                        </td>
                        <td class="td-judul">{{ $data->judul }}</td>
                        <td class="td-meta">{{ $data->pengarang }}</td>
                        <td class="td-meta">{{ $data->penerbit }}</td>
                        <td class="td-meta">{{ $data->tahun_terbit }}</td>
                        <td>
                            <span class="kat-pill">{{ optional($data->kategori)->nama_kategori ?? $data->kategori }}</span>
                        </td>
                        <td>
                            <span class="stok-badge {{ $data->stok > 0 ? 'ada' : 'habis' }}">
                                {{ $data->stok > 0 ? $data->stok : 'Habis' }}
                            </span>
                        </td>
                        <td class="td-sinopsis">{{ $data->sinopsis }}</td>
                        <td>
                            <div class="act-wrap">
                                <button wire:click="edit({{ $data->id }})" class="btn-act edit">
                                    <i class="fas fa-pen"></i> Edit
                                </button>
                                <button wire:click="destroy({{ $data->id }})" wire:confirm="Hapus buku ini?" class="btn-act delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="buku-empty">
                                <div class="buku-empty-icon"><i class="fas fa-book-open"></i></div>
                                <p>Belum ada data buku.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        <div class="buku-footer">
            {{ $this->bukus->links() }}
        </div>

    </div>

    {{-- ══════════════════════════════════════ --}}
    {{-- ── Modal Tambah ──                    --}}
    {{-- ══════════════════════════════════════ --}}
    @if($addPage)
    <div class="buku-modal-overlay" wire:click.self="$set('addPage', false)">
        <div class="buku-modal">
            <div class="buku-modal-header">
                <p class="buku-modal-title">
                    <i class="fas fa-book-medical" style="margin-right:8px;color:#3B82F6;"></i>Tambah Buku
                </p>
                <button class="buku-modal-close" wire:click="$set('addPage', false)">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <div class="buku-modal-body">
                <div class="mform-grid">

                    <div class="mfield mform-full">
                        <label class="mfield-label">Judul Buku</label>
                        <input type="text" class="mfield-input" wire:model="judul" placeholder="Masukkan judul buku">
                        @error('judul') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield">
                        <label class="mfield-label">Pengarang</label>
                        <input type="text" class="mfield-input" wire:model="pengarang" placeholder="Nama pengarang">
                        @error('pengarang') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield">
                        <label class="mfield-label">Penerbit</label>
                        <input type="text" class="mfield-input" wire:model="penerbit" placeholder="Nama penerbit">
                        @error('penerbit') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield">
                        <label class="mfield-label">Tahun Terbit</label>
                        <input type="number" class="mfield-input" wire:model="tahun_terbit" placeholder="Contoh: 2023">
                        @error('tahun_terbit') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield">
                        <label class="mfield-label">Kategori</label>
                        @php $_kategoris = App\Models\Kategori::orderBy('nama_kategori')->get(); @endphp
                        <select class="mfield-select" wire:model="kategori_id">
                            <option value="">Pilih Kategori</option>
                            @foreach($_kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield">
                        <label class="mfield-label">Stok</label>
                        <input type="number" class="mfield-input" wire:model="stok" placeholder="Jumlah stok" min="0">
                        @error('stok') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield mform-full">
                        <label class="mfield-label">Cover Buku</label>
                        <label class="mfield-file-wrap">
                            <input type="file" wire:model="foto" accept="image/*">
                            <div class="mfield-file-icon"><i class="fas fa-cloud-arrow-up"></i></div>
                            <p class="mfield-file-text">
                                <strong>Klik untuk upload</strong> atau drag & drop<br>
                                <span style="font-size:0.72rem;color:#94A3B8;">PNG, JPG, JPEG (maks. 2MB)</span>
                            </p>
                        </label>
                        @if($foto)
                            <img src="{{ $foto->temporaryUrl() }}" class="foto-preview" alt="Preview">
                        @endif
                        @error('foto') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield mform-full">
                        <label class="mfield-label">Sinopsis</label>
                        <textarea class="mfield-textarea" wire:model="sinopsis" placeholder="Tulis sinopsis buku..."></textarea>
                        @error('sinopsis') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="modal-actions">
                    <button wire:click="store" class="btn-modal-save">
                        <i class="fas fa-check"></i> Simpan Buku
                    </button>
                    <button wire:click="$set('addPage', false)" class="btn-modal-cancel">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════ --}}
    {{-- ── Modal Edit ──                      --}}
    {{-- ══════════════════════════════════════ --}}
    @if($editPage)
    <div class="buku-modal-overlay" wire:click.self="$set('editPage', false)">
        <div class="buku-modal">
            <div class="buku-modal-header">
                <p class="buku-modal-title">
                    <i class="fas fa-book-open" style="margin-right:8px;color:#3B82F6;"></i>Edit Buku
                </p>
                <button class="buku-modal-close" wire:click="$set('editPage', false)">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <div class="buku-modal-body">
                <div class="mform-grid">

                    <div class="mfield mform-full">
                        <label class="mfield-label">Judul Buku</label>
                        <input type="text" class="mfield-input" wire:model="judul" placeholder="Masukkan judul buku">
                        @error('judul') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield">
                        <label class="mfield-label">Pengarang</label>
                        <input type="text" class="mfield-input" wire:model="pengarang" placeholder="Nama pengarang">
                        @error('pengarang') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield">
                        <label class="mfield-label">Penerbit</label>
                        <input type="text" class="mfield-input" wire:model="penerbit" placeholder="Nama penerbit">
                        @error('penerbit') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield">
                        <label class="mfield-label">Tahun Terbit</label>
                        <input type="number" class="mfield-input" wire:model="tahun_terbit" placeholder="Contoh: 2023">
                        @error('tahun_terbit') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield">
                        <label class="mfield-label">Kategori</label>
                        @php $_kategoris = App\Models\Kategori::orderBy('nama_kategori')->get(); @endphp
                        <select class="mfield-select" wire:model="kategori_id">
                            <option value="">Pilih Kategori</option>
                            @foreach($_kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield">
                        <label class="mfield-label">Stok</label>
                        <input type="number" class="mfield-input" wire:model="stok" placeholder="Jumlah stok" min="0">
                        @error('stok') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield mform-full">
                        <label class="mfield-label">Ganti Cover <span style="font-weight:400;text-transform:none;letter-spacing:0;">(opsional)</span></label>
                        <label class="mfield-file-wrap">
                            <input type="file" wire:model="foto" accept="image/*">
                            <div class="mfield-file-icon"><i class="fas fa-cloud-arrow-up"></i></div>
                            <p class="mfield-file-text">
                                <strong>Klik untuk upload</strong> foto baru<br>
                                <span style="font-size:0.72rem;color:#94A3B8;">Biarkan kosong jika tidak ingin mengubah cover</span>
                            </p>
                        </label>
                        @if($foto)
                            <img src="{{ $foto->temporaryUrl() }}" class="foto-preview" alt="Preview baru">
                        @endif
                        @error('foto') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                    <div class="mfield mform-full">
                        <label class="mfield-label">Sinopsis</label>
                        <textarea class="mfield-textarea" wire:model="sinopsis" placeholder="Tulis sinopsis buku..."></textarea>
                        @error('sinopsis') <p class="err-msg">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="modal-actions">
                    <button wire:click="update" class="btn-modal-save">
                        <i class="fas fa-check"></i> Update Buku
                    </button>
                    <button wire:click="$set('editPage', false)" class="btn-modal-cancel">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>