<?php
use App\Models\Pinjam;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Carbon\Carbon;

new class extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';

    public $dateFrom = '';
    public $dateTo   = '';

    public function updatingDateFrom() { $this->resetPage(); }
    public function updatingDateTo()   { $this->resetPage(); }

    public function resetFilter()
    {
        $this->dateFrom = '';
        $this->dateTo   = '';
        $this->resetPage();
    }

    public function getPinjamProperty()
    {
        return Pinjam::with(['buku', 'user'])
            ->where('status', 'dikembalikan')
            ->when($this->dateFrom, fn($q) => $q->whereDate('tanggal_kembali', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('tanggal_kembali', '<=', $this->dateTo))
            ->latest()
            ->paginate(10);
    }

    public function getTotalDendaProperty()
    {
        return Pinjam::where('status', 'dikembalikan')
            ->when($this->dateFrom, fn($q) => $q->whereDate('tanggal_kembali', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('tanggal_kembali', '<=', $this->dateTo))
            ->sum('denda');
    }

    public function getTotalPinjamanProperty()
    {
        return Pinjam::where('status', 'dikembalikan')
            ->when($this->dateFrom, fn($q) => $q->whereDate('tanggal_kembali', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('tanggal_kembali', '<=', $this->dateTo))
            ->count();
    }
};
?>

<style>
    .laporan-wrap { font-family: 'DM Sans', 'Segoe UI', sans-serif; }

    /* ── Alert ── */
    .lap-alert {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 18px; border-radius: 12px;
        font-size: 0.85rem; font-weight: 500; margin-bottom: 20px;
        background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7;
    }

    /* ── Card ── */
    .lap-card {
        background: #FFFFFF;
        border: 1.5px solid #DBEAFE;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(37,99,235,0.06);
    }

    /* ── Header ── */
    .lap-header {
        background: #EFF6FF;
        border-bottom: 1.5px solid #DBEAFE;
        padding: 20px 26px;
        display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }
    .lap-header-left { display: flex; align-items: center; gap: 12px; }
    .lap-header-icon {
        width: 40px; height: 40px; background: #FFFFFF;
        border: 1.5px solid #DBEAFE; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #3B82F6; font-size: 1rem; flex-shrink: 0;
    }
    .lap-header-title { font-size: 0.97rem; font-weight: 700; color: #0F172A; margin: 0; }
    .lap-header-sub   { font-size: 0.75rem; color: #94A3B8; margin: 0; }

    /* ── Summary Cards ── */
    .lap-summary {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 14px;
        padding: 20px 26px 0;
    }
    @media (max-width: 576px) { .lap-summary { grid-template-columns: 1fr; } }

    .sum-card {
        border-radius: 14px;
        padding: 16px 18px;
        display: flex; align-items: center; gap: 14px;
    }
    .sum-card.total { background: #EFF6FF; border: 1.5px solid #DBEAFE; }
    .sum-card.denda { background: #FFF1F2; border: 1.5px solid #FFE4E6; }
    .sum-icon {
        width: 40px; height: 40px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;
    }
    .sum-card.total .sum-icon { background: #DBEAFE; color: #2563EB; }
    .sum-card.denda .sum-icon { background: #FFE4E6; color: #EF4444; }
    .sum-number {
        font-size: 1.5rem; font-weight: 800; line-height: 1; margin-bottom: 2px;
    }
    .sum-card.total .sum-number { color: #2563EB; }
    .sum-card.denda .sum-number { color: #EF4444; }
    .sum-label { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .sum-card.total .sum-label { color: #60A5FA; }
    .sum-card.denda .sum-label { color: #F87171; }

    /* ── Date Filter ── */
    .lap-filter {
        padding: 18px 26px;
        border-bottom: 1px solid #F1F5F9;
        display: flex; align-items: flex-end; gap: 12px; flex-wrap: wrap;
    }
    .lap-filter-group { display: flex; flex-direction: column; gap: 5px; }
    .lap-filter-label {
        font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.7px; color: #94A3B8;
    }
    .lap-filter-input {
        padding: 9px 14px;
        border: 1.5px solid #DBEAFE;
        border-radius: 10px;
        font-size: 0.85rem;
        color: #334155;
        background: #FFFFFF;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: inherit;
        min-width: 150px;
    }
    .lap-filter-input:focus {
        border-color: #60A5FA;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.15);
    }
    .lap-filter-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 18px; border-radius: 10px;
        font-size: 0.8rem; font-weight: 700;
        border: none; cursor: pointer; font-family: inherit;
        transition: opacity 0.15s, transform 0.12s;
    }
    .lap-filter-btn:hover { opacity: 0.85; transform: translateY(-1px); }
    .lap-filter-btn.reset {
        background: #F1F5F9 !important; color: #64748B !important;
    }
    .lap-active-badge {
        display: inline-flex; align-items: center; gap: 5px;
        background: #EFF6FF; color: #2563EB;
        border: 1px solid #DBEAFE;
        font-size: 0.72rem; font-weight: 700;
        padding: 4px 10px; border-radius: 100px;
        align-self: flex-end; margin-bottom: 2px;
    }

    /* ── Table ── */
    .lap-table-wrap { overflow-x: auto; }
    .lap-table { width: 100%; border-collapse: collapse; font-size: 0.845rem; }
    .lap-table thead tr { background: #F8FAFC; border-bottom: 1.5px solid #DBEAFE; }
    .lap-table thead th {
        padding: 13px 16px; font-size: 0.7rem; font-weight: 700;
        letter-spacing: 0.8px; text-transform: uppercase; color: #3B82F6; white-space: nowrap;
    }
    .lap-table tbody tr { border-bottom: 1px solid #F1F5F9; transition: background 0.15s; }
    .lap-table tbody tr:last-child { border-bottom: none; }
    .lap-table tbody tr:hover { background: #EFF6FF; }
    .lap-table tbody td { padding: 13px 16px; color: #334155; vertical-align: middle; white-space: nowrap; }

    .td-no { font-size: 0.74rem; font-weight: 700; color: #CBD5E1; text-align: center; width: 36px; }
    .td-buku { font-weight: 700; color: #0F172A; max-width: 160px; white-space: normal; line-height: 1.4; }
    .td-date { color: #64748B; font-size: 0.82rem; }

    .status-pill {
        display: inline-flex; align-items: center; gap: 4px;
        background: #F0FDF4; color: #16A34A;
        border: 1px solid #DCFCE7;
        font-size: 0.7rem; font-weight: 700;
        padding: 3px 10px; border-radius: 100px;
    }
    .status-dot { width: 5px; height: 5px; border-radius: 50%; background: #16A34A; }

    /* ── Footer ── */
    .lap-footer {
        padding: 16px 26px;
        border-top: 1px solid #F1F5F9;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
    }

    .btn-cetak {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 22px; border-radius: 11px;
        font-size: 0.85rem; font-weight: 700;
        background: #2563EB !important; color: #ffffff !important;
        border: none; text-decoration: none;
        box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        transition: background 0.2s, transform 0.15s;
    }
    .btn-cetak:hover { background: #1D4ED8 !important; color: #ffffff !important; transform: translateY(-1px); }

    /* ── Empty ── */
    .lap-empty { padding: 52px 20px; text-align: center; }
    .lap-empty-icon {
        width: 60px; height: 60px; background: #EFF6FF; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px; font-size: 1.4rem; color: #BFDBFE;
    }
    .lap-empty p { color: #94A3B8; font-size: 0.88rem; margin: 0; }

    /* ── Pagination ── */
    .laporan-wrap .pagination { margin: 0; gap: 4px; justify-content: center; }
    .laporan-wrap .page-link {
        border-radius: 8px !important; border: 1.5px solid #DBEAFE;
        color: #2563EB; font-size: 0.82rem; font-weight: 600;
        padding: 6px 12px; transition: all 0.15s;
    }
    .laporan-wrap .page-link:hover { background: #EFF6FF; border-color: #93C5FD; color: #1D4ED8; }
    .laporan-wrap .page-item.active .page-link {
        background: #2563EB !important; border-color: #2563EB !important;
        color: white !important; box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    }
    .laporan-wrap .page-item.disabled .page-link {
        color: #CBD5E1; border-color: #F1F5F9; background: #F8FAFC;
    }
</style>

<div class="laporan-wrap">

    {{-- ── Alert ── --}}
    @if(session()->has('message'))
        <div class="lap-alert">
            <i class="fas fa-circle-check"></i> {{ session('message') }}
        </div>
    @endif

    <div class="lap-card">

        {{-- ── Header ── --}}
        <div class="lap-header">
            <div class="lap-header-left">
                <div class="lap-header-icon"><i class="fas fa-chart-bar"></i></div>
                <div>
                    <p class="lap-header-title">Laporan Peminjaman</p>
                    <p class="lap-header-sub">Data buku yang telah dikembalikan</p>
                </div>
            </div>
        </div>

        {{-- ── Summary Cards ── --}}
        <div class="lap-summary">
            <div class="sum-card total">
                <div class="sum-icon"><i class="fas fa-book-open"></i></div>
                <div>
                    <div class="sum-number">{{ $this->totalPinjaman }}</div>
                    <div class="sum-label">Total Dikembalikan</div>
                </div>
            </div>
        
        </div>

        {{-- ── Date Filter ── --}}
        <div class="lap-filter">
            <div class="lap-filter-group">
                <span class="lap-filter-label"><i class="fas fa-calendar" style="margin-right:3px;"></i>Dari Tanggal</span>
                <input type="date" class="lap-filter-input" wire:model.live="dateFrom">
            </div>
            <div class="lap-filter-group">
                <span class="lap-filter-label"><i class="fas fa-calendar" style="margin-right:3px;"></i>Sampai Tanggal</span>
                <input type="date" class="lap-filter-input" wire:model.live="dateTo">
            </div>
            @if($dateFrom || $dateTo)
                <button wire:click="resetFilter" class="lap-filter-btn reset">
                    <i class="fas fa-xmark"></i> Reset
                </button>
                <span class="lap-active-badge">
                    <i class="fas fa-filter" style="font-size:0.6rem;"></i>
                    Filter aktif
                </span>
            @endif
        </div>

        {{-- ── Table ── --}}
        <div class="lap-table-wrap">
            <table class="lap-table">
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
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->pinjam as $data)
                    <tr>
                        <td class="td-no">{{ ($this->pinjam->currentPage() - 1) * $this->pinjam->perPage() + $loop->iteration }}</td>
                        <td class="td-buku">{{ $data->buku->judul ?? '-' }}</td>
                        <td style="font-weight:500;">{{ $data->nama_peminjam ?? '-' }}</td>
                        <td style="color:#64748B;font-size:0.82rem;">{{ $data->no_telp ?? '-' }}</td>
                        <td class="td-date">{{ $data->tanggal_pinjam?->format('d M Y') ?? '-' }}</td>
                        <td class="td-date">
                            @if(in_array($data->status, ['dipinjam','dikembalikan','pengembalian_ditolak']))
                                {{ $data->perkiraan_kembali?->format('d M Y') ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="td-date">{{ $data->tanggal_kembali?->format('d M Y') ?? '-' }}</td>
                        <td>
                            @if($data->denda > 0)
                                <span style="color:#EF4444;font-weight:700;font-size:0.82rem;">
                                    Rp {{ number_format($data->denda, 0, ',', '.') }}
                                </span>
                            @else
                                <span style="color:#CBD5E1;font-size:0.82rem;">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-pill">
                                <span class="status-dot"></span>
                                Dikembalikan
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="lap-empty">
                                <div class="lap-empty-icon"><i class="fas fa-book-open"></i></div>
                                <p>Belum ada data laporan peminjaman.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Footer ── --}}
        <div class="lap-footer">
            <div>{{ $this->pinjam->links() }}</div>
            <a href="{{ route('laporan.print') }}{{ ($dateFrom || $dateTo) ? '?from='.$dateFrom.'&to='.$dateTo : '' }}"
               target="_blank"
               class="btn-cetak">
                <i class="fas fa-print"></i> Cetak Laporan
            </a>
        </div>

    </div>
</div>