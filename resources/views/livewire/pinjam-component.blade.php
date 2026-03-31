<?php

use App\Models\Buku;
use App\Models\Pinjam;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Component;
use Carbon\Carbon;

new class extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $user_id;
    public $buku_id;
    public $nama_peminjam;
    public $no_telp;
    public $tanggal_pinjam;
    public $tanggal_kembali;
    public $status = 'menunggu';
    public $filterStatus = '';

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function getPinjamProperty()
    {
        return Pinjam::with(['buku', 'user'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(5);
    }

    public function getCountDipinjamProperty()   { return Pinjam::where('status', 'dipinjam')->count(); }
    public function getCountDikembalikanProperty() { return Pinjam::where('status', 'dikembalikan')->count(); }
    public function getCountDitolakProperty()     { return Pinjam::where('status', 'ditolak')->count(); }
    public function getCountMenungguProperty()    { return Pinjam::where('status', 'menunggu')->count(); }
    public function getCountPengembalianMenungguProperty() { return Pinjam::where('status', 'pengembalian_menunggu')->count(); }
    public function getCountPengembalianDitolakProperty()  { return Pinjam::where('status', 'pengembalian_ditolak')->count(); }

    public function dipinjam($id)
    {
        $pinjam = Pinjam::find($id);
        if (!$pinjam || $pinjam->status === 'dipinjam') return;

        $buku = $pinjam->buku;
        if ($buku && $buku->stok > 0) {
            $buku->decrement('stok');
            $pinjam->update([
                'status' => 'dipinjam',
                'tanggal_pinjam' => now()->toDateString(),
                'perkiraan_kembali' => now()->addDays(14)->toDateString(),
                'tanggal_kembali' => null,
            ]);
            session()->flash('message', 'Berhasil dipinjam! Est. kembali: ' . now()->addDays(14)->format('d M Y'));
        }
    }

    public function dikembalikan($id)
    {
        $pinjam = Pinjam::find($id);
        if (!$pinjam || $pinjam->status === 'dikembalikan' || $pinjam->status === 'pengembalian_menunggu') return;

        $pinjam->update([
            'status' => 'pengembalian_menunggu',
            'tanggal_kembali' => now(),
        ]);
        session()->flash('message', 'Pengajuan pengembalian dikirim, menunggu approval!');
    }

    public function ditolak($id)
    {
        $pinjam = Pinjam::find($id);
        $pinjam->update(['status' => 'ditolak', 'tanggal_kembali' => null]);
        session()->flash('message', 'Berhasil ditolak!');
    }

    public function approvePengembalian($id)
    {
        $pinjam = Pinjam::find($id);
        if (!$pinjam || $pinjam->status !== 'pengembalian_menunggu') return;

        if ($pinjam->buku) $pinjam->buku->increment('stok');

        $pinjam->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now()
        ]);
        session()->flash('message', 'Pengembalian disetujui!');
    }

    public function tolakPengembalian($id)
    {
        $pinjam = Pinjam::find($id);
        if (!$pinjam || $pinjam->status !== 'pengembalian_menunggu') return;

        $pinjam->update([
            'status' => 'pengembalian_ditolak',
            'tanggal_kembali' => null
        ]);
        session()->flash('message', 'Pengembalian ditolak!');
    }

    public function store()
    {
        $this->validate([
            'nama_peminjam' => 'required',
            'no_telp'       => 'required',
            'tanggal_pinjam' => 'required|date',
        ]);

        $buku = Buku::find($this->buku_id);
        if (!$buku || $buku->stok < 1) {
            session()->flash('error', 'Stok buku tidak tersedia!');
            return;
        }

        Pinjam::create([
            'user_id'       => auth()->id(),
            'buku_id'       => $this->buku_id,
            'nama_peminjam' => $this->nama_peminjam,
            'no_telp'       => $this->no_telp,
            'tanggal_pinjam' => $this->tanggal_pinjam,
            'status'        => 'menunggu',
        ]);

        session()->flash('success', 'Permintaan peminjaman dibuat, status menunggu.');
        return redirect()->route('koleksi');
    }
};

?>

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
        --red-600:  #DC2626;
        --yellow-50:#FFFBEB;
        --yellow-100:#FEF3C7;
        --yellow-600:#D97706;
    }

    .pinjam-wrapper {
        font-family: 'DM Sans', 'Segoe UI', sans-serif;
    }

    /* ── Card ── */
    .pinjam-card {
        background: var(--white);
        border: 1.5px solid var(--blue-100);
        border-radius: 20px;
        padding: 28px 28px 24px;
        box-shadow: 0 2px 16px rgba(37,99,235,0.06);
    }

    /* ── Header ── */
    .pinjam-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }
    .pinjam-header-icon {
        width: 42px; height: 42px;
        background: var(--blue-50);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: var(--blue-600);
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .pinjam-header-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
        letter-spacing: -0.2px;
    }
    .pinjam-header-sub {
        font-size: 0.76rem;
        color: var(--gray-400);
        margin: 0;
    }

    /* ── Alert ── */
    .pinjam-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 20px;
    }
    .pinjam-alert.success {
        background: var(--green-50);
        color: var(--green-600);
        border: 1px solid var(--green-100);
    }
    .pinjam-alert.error {
        background: var(--red-50);
        color: var(--red-600);
        border: 1px solid var(--red-100);
    }

    /* ── Table wrapper ── */
    .pinjam-table-wrap {
        overflow-x: auto;
        border-radius: 14px;
        border: 1.5px solid var(--blue-100);
    }
    .pinjam-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.845rem;
    }
    .pinjam-table thead tr {
        background: var(--blue-50);
    }
    .pinjam-table thead th {
        padding: 13px 16px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: var(--blue-600);
        white-space: nowrap;
        border-bottom: 1.5px solid var(--blue-100);
        border-right: none;
    }
    .pinjam-table thead th:first-child { border-radius: 0; }

    .pinjam-table tbody tr {
        border-bottom: 1px solid var(--gray-100);
        transition: background 0.15s;
    }
    .pinjam-table tbody tr:last-child { border-bottom: none; }
    .pinjam-table tbody tr:hover { background: var(--blue-50); }

    .pinjam-table tbody td {
        padding: 14px 16px;
        color: var(--gray-700);
        vertical-align: middle;
        white-space: nowrap;
    }

    .td-no {
        font-weight: 700;
        color: var(--gray-400);
        font-size: 0.78rem;
        width: 36px;
        text-align: center;
    }
    .td-buku {
        font-weight: 600;
        color: var(--gray-900);
        max-width: 160px;
        white-space: normal;
        line-height: 1.4;
    }
    .td-nama { font-weight: 500; }
    .td-date {
        color: var(--gray-500);
        font-size: 0.82rem;
    }
    .td-denda {
        font-weight: 600;
        color: var(--red-600);
        font-size: 0.82rem;
    }
    .td-denda.none { color: var(--gray-300); font-weight: 400; }

    /* ── Status Badge ── */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 11px;
        border-radius: 100px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .status-badge .dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .status-badge.menunggu  { background: var(--yellow-50);  color: var(--yellow-600); border: 1px solid var(--yellow-100); }
    .status-badge.menunggu .dot  { background: var(--yellow-600); }
    .status-badge.dipinjam  { background: var(--blue-50);    color: var(--blue-600);   border: 1px solid var(--blue-100); }
    .status-badge.dipinjam .dot  { background: var(--blue-500); }
    .status-badge.dikembalikan { background: var(--green-50); color: var(--green-600); border: 1px solid var(--green-100); }
    .status-badge.dikembalikan .dot { background: var(--green-600); }
    .status-badge.ditolak   { background: var(--red-50);     color: var(--red-600);    border: 1px solid var(--red-100); }
    .status-badge.ditolak .dot   { background: var(--red-600); }
    .status-badge.pengembalian_menunggu { background: var(--yellow-50); color: var(--yellow-600); border: 1px solid var(--yellow-100); }
    .status-badge.pengembalian_menunggu .dot { background: var(--yellow-600); }
    .status-badge.pengembalian_ditolak { background: var(--red-50); color: var(--red-600); border: 1px solid var(--red-100); }
    .status-badge.pengembalian_ditolak .dot { background: var(--red-600); }

    /* ── Action Buttons ── */
    .action-wrap { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.76rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: opacity 0.15s, transform 0.12s, box-shadow 0.15s;
    }
    .btn-action:hover { opacity: 0.88; transform: translateY(-1px); }

    .btn-action.confirm {
        background: #16A34A !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(22,163,74,0.25);
        border: none;
    }
    .btn-action.reject {
        background: #EF4444 !important;
        color: #ffffff !important;
        border: none;
    }
    .btn-action.print {
        background: #2563EB !important;
        color: #ffffff !important;
        border: none;
        box-shadow: 0 2px 8px rgba(37,99,235,0.2);
    }
    .btn-action.print:hover { background: #1D4ED8 !important; }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 48px 20px;
    }
    .empty-state-icon {
        width: 60px; height: 60px;
        background: var(--blue-50);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px;
        font-size: 1.4rem;
        color: var(--blue-200);
    }
    .empty-state p {
        color: var(--gray-400);
        font-size: 0.88rem;
        margin: 0;
    }

    /* ── Pagination override ── */
    .pinjam-wrapper .pagination {
        margin-top: 20px;
        gap: 4px;
        justify-content: center;
    }
    .pinjam-wrapper .page-link {
        border-radius: 8px !important;
        border: 1.5px solid var(--blue-100);
        color: var(--blue-600);
        font-size: 0.83rem;
        font-weight: 600;
        padding: 6px 12px;
        transition: all 0.15s;
    }
    .pinjam-wrapper .page-link:hover {
        background: var(--blue-50);
        border-color: var(--blue-300);
        color: var(--blue-700);
    }
    .pinjam-wrapper .page-item.active .page-link {
        background: var(--blue-600);
        border-color: var(--blue-600);
        color: white;
        box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    }
    .pinjam-wrapper .page-item.disabled .page-link {
        color: var(--gray-300);
        border-color: var(--gray-100);
        background: var(--gray-50);
    }

    /* ── Stat Cards ── */
    .stat-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }
    @media (max-width: 768px) { .stat-cards { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .stat-cards { grid-template-columns: 1fr 1fr; } }

    .stat-card {
        border-radius: 16px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 14px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .stat-card.active { border-color: currentColor; box-shadow: 0 6px 20px rgba(0,0,0,0.1); }

    .stat-card.menunggu  { background: #FFFBEB; }
    .stat-card.dipinjam  { background: #EFF6FF; }
    .stat-card.dikembalikan { background: #F0FDF4; }
    .stat-card.ditolak   { background: #FFF1F2; }

    .stat-icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .stat-card.menunggu  .stat-icon { background: #FEF3C7; color: #D97706; }
    .stat-card.dipinjam  .stat-icon { background: #DBEAFE; color: #2563EB; }
    .stat-card.dikembalikan .stat-icon { background: #DCFCE7; color: #16A34A; }
    .stat-card.ditolak   .stat-icon { background: #FFE4E6; color: #EF4444; }

    .stat-info { min-width: 0; }
    .stat-number {
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 3px;
    }
    .stat-card.menunggu  .stat-number { color: #D97706; }
    .stat-card.dipinjam  .stat-number { color: #2563EB; }
    .stat-card.dikembalikan .stat-number { color: #16A34A; }
    .stat-card.ditolak   .stat-number { color: #EF4444; }

    .stat-label {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-card.menunggu  .stat-label { color: #D97706; }
    .stat-card.dipinjam  .stat-label { color: #2563EB; }
    .stat-card.dikembalikan .stat-label { color: #16A34A; }
    .stat-card.ditolak   .stat-label { color: #EF4444; }

    .stat-active-dot {
        position: absolute;
        top: 10px; right: 10px;
        width: 8px; height: 8px;
        border-radius: 50%;
        display: none;
    }
    .stat-card.active .stat-active-dot { display: block; }
    .stat-card.menunggu.active  .stat-active-dot { background: #D97706; }
    .stat-card.dipinjam.active  .stat-active-dot { background: #2563EB; }
    .stat-card.dikembalikan.active .stat-active-dot { background: #16A34A; }
    .stat-card.ditolak.active   .stat-active-dot { background: #EF4444; }

    /* active filter label */
    .filter-active-label {
        display: inline-flex; align-items: center; gap: 7px;
        background: #EFF6FF; color: #2563EB;
        border: 1px solid #DBEAFE;
        font-size: 0.75rem; font-weight: 700;
        padding: 4px 12px; border-radius: 100px;
        margin-bottom: 14px;
        cursor: pointer;
    }
    .filter-active-label:hover { background: #DBEAFE; }
</style>

<div class="pinjam-wrapper">
    <div class="pinjam-card">

        {{-- ── Alert ── --}}
        @if(session()->has('message'))
            <div class="pinjam-alert success">
                <i class="fas fa-circle-check"></i>
                {{ session('message') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="pinjam-alert error">
                <i class="fas fa-circle-exclamation"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- ── Header ── --}}
        <div class="pinjam-header">
            <div class="pinjam-header-icon">
                <i class="fas fa-book-bookmark"></i>
            </div>
            <div>
                <p class="pinjam-header-title">Daftar Peminjaman</p>
                <p class="pinjam-header-sub">Kelola semua permintaan peminjaman buku</p>
            </div>
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="stat-cards">
            <div class="stat-card menunggu {{ $filterStatus === 'menunggu' ? 'active' : '' }}"
                 wire:click="$set('filterStatus', $filterStatus === 'menunggu' ? '' : 'menunggu')">
                <div class="stat-active-dot"></div>
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $this->countMenunggu }}</div>
                    <div class="stat-label">Menunggu</div>
                </div>
            </div>

            <div class="stat-card dipinjam {{ $filterStatus === 'dipinjam' ? 'active' : '' }}"
                 wire:click="$set('filterStatus', $filterStatus === 'dipinjam' ? '' : 'dipinjam')">
                <div class="stat-active-dot"></div>
                <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $this->countDipinjam }}</div>
                    <div class="stat-label">Dipinjam</div>
                </div>
            </div>

            <div class="stat-card pengembalian_menunggu {{ $filterStatus === 'pengembalian_menunggu' ? 'active' : '' }}"
                 wire:click="$set('filterStatus', $filterStatus === 'pengembalian_menunggu' ? '' : 'pengembalian_menunggu')">
                <div class="stat-active-dot"></div>
                <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $this->countPengembalianMenunggu }}</div>
                    <div class="stat-label">Menunggu Approval</div>
                </div>
            </div>

            <div class="stat-card dikembalikan {{ $filterStatus === 'dikembalikan' ? 'active' : '' }}"
                 wire:click="$set('filterStatus', $filterStatus === 'dikembalikan' ? '' : 'dikembalikan')">
                <div class="stat-active-dot"></div>
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $this->countDikembalikan }}</div>
                    <div class="stat-label">Dikembalikan</div>
                </div>
            </div>

            <div class="stat-card ditolak {{ $filterStatus === 'ditolak' ? 'active' : '' }}"
                 wire:click="$set('filterStatus', $filterStatus === 'ditolak' ? '' : 'ditolak')">
                <div class="stat-active-dot"></div>
                <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $this->countDitolak }}</div>
                    <div class="stat-label">Ditolak</div>
                </div>
            </div>

            <div class="stat-card pengembalian_ditolak {{ $filterStatus === 'pengembalian_ditolak' ? 'active' : '' }}"
                 wire:click="$set('filterStatus', $filterStatus === 'pengembalian_ditolak' ? '' : 'pengembalian_ditolak')">
                <div class="stat-active-dot"></div>
                <div class="stat-icon"><i class="fas fa-ban"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $this->countPengembalianDitolak }}</div>
                    <div class="stat-label">Pengembalian Ditolak</div>
                </div>
            </div>
        </div>

        {{-- Active filter label --}}
        @if($filterStatus)
            <div class="filter-active-label" wire:click="$set('filterStatus','')">
                <i class="fas fa-filter" style="font-size:0.65rem;"></i>
                Filter: {{ ucfirst($filterStatus) }}
                <i class="fas fa-xmark" style="font-size:0.65rem;"></i>
            </div>
        @endif

        {{-- ── Table ── --}}
        <div class="pinjam-table-wrap">
            <table class="pinjam-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">#</th>
                        <th>Buku</th>
                        <th>Nama Member</th>
                        <th>No. Telepon</th>
                        <th>Tgl. Pinjam</th>
                        <th>Est. Kembali</th>
                        <th>Tgl. Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->pinjam as $data)
                    <tr>
                        <td class="td-no">{{ $loop->iteration }}</td>

                        <td class="td-buku">{{ $data->buku->judul ?? '-' }}</td>

                        <td class="td-nama">{{ $data->nama_peminjam ?? '-' }}</td>

                        <td style="color:var(--gray-500);font-size:0.82rem;">
                            {{ $data->no_telp ?? '-' }}
                        </td>

                        <td class="td-date">
                            {{ $data->tanggal_pinjam?->format('d M Y') ?? '-' }}
                        </td>

                        <td class="td-date">
                         @if($data->status === 'dipinjam' || $data->status === 'dikembalikan' || $data->status === 'pengembalian_ditolak' || $data->status === 'pengembalian_menunggu')
                         {{ $data->perkiraan_kembali?->format('d M Y') ?? '-' }}
                         @else
                         <span style="color:var(--gray-300);">—</span>
                         @endif
                         </td>

                        <td class="td-date">
                            @if($data->status === 'dikembalikan')
                                {{ $data->tanggal_kembali?->format('d M Y') }}
                            @else
                                <span style="color:var(--gray-300);">—</span>
                            @endif
                        </td>

                        <td>
                            @if($data->status === 'dikembalikan')
                                <span class="td-denda">Rp {{ number_format($data->denda,0,',','.') }}</span>
                            @else
                                <span style="color:var(--gray-300);font-size:0.82rem;">—</span>
                            @endif
                        </td>

                        <td>
                            <span class="status-badge {{ $data->status }}">
                                <span class="dot"></span>
                                @switch($data->status)
                                    @case('menunggu')
                                        Menunggu
                                        @break
                                    @case('dipinjam')
                                        Dipinjam
                                        @break
                                    @case('dikembalikan')
                                        Dikembalikan
                                        @break
                                    @case('ditolak')
                                        Ditolak
                                        @break
                                    @case('pengembalian_menunggu')
                                        Menunggu Approval
                                        @break
                                    @case('pengembalian_ditolak')
                                        Pengembalian Ditolak
                                        @break
                                    @default
                                        {{ ucfirst($data->status) }}
                                @endswitch
                            </span>
                        </td>

                        <td>
    <div class="action-wrap">

        {{-- Status menunggu (permintaan pinjam baru) --}}
        @if($data->status === 'menunggu')
            <button class="btn-action confirm"
                wire:click="dipinjam({{ $data->id }})">
                <i class="fas fa-check"></i> Pinjam
            </button>

            <button class="btn-action reject"
                wire:click="ditolak({{ $data->id }})">
                <i class="fas fa-xmark"></i> Tolak
            </button>

        {{-- Status pengembalian_menunggu --}}
        @elseif($data->status === 'pengembalian_menunggu')
            <button class="btn-action confirm"
                wire:click="approvePengembalian({{ $data->id }})">
                <i class="fas fa-check"></i> Setujui Pengembalian
            </button>

            <button class="btn-action reject"
                wire:click="tolakPengembalian({{ $data->id }})">
                <i class="fas fa-xmark"></i> Tolak Pengembalian
            </button>

        {{-- Status dipinjam --}}
        @elseif($data->status === 'dipinjam')

            <a href="{{ route('pinjam.print', $data->id) }}"
                target="_blank" class="btn-action print">
                <i class="fas fa-print"></i> Cetak
            </a>

        {{-- Status dikembalikan --}}
        @elseif($data->status === 'dikembalikan')

            <a href="{{ route('pinjam.print', $data->id) }}"
                target="_blank" class="btn-action print">
                <i class="fas fa-print"></i> Cetak
            </a>

        @endif

    </div>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <p>Belum ada data peminjaman tersedia</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        <div>{{ $this->pinjam->links() }}</div>

    </div>
</div>