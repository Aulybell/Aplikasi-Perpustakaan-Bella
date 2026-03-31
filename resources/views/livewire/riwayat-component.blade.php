<?php

use App\Models\Pinjam;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

new class extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';
    public $filterStatus = '';

    protected $queryString = [
        'filterStatus' => ['except' => '']
    ];

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function getRiwayatProperty()
    {
        return Pinjam::with('buku')
            ->where('user_id', Auth::id())
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate(5);
    }

    public function kembalikan($pinjamId)
    {
        $pinjam = Pinjam::find($pinjamId);
        if ($pinjam && $pinjam->user_id == Auth::id() && ($pinjam->status == 'dipinjam' || $pinjam->status == 'pengembalian_ditolak')) {
            $pinjam->status = 'pengembalian_menunggu';
            $pinjam->save();
            session()->flash('message', 'Pengajuan pengembalian dikirim, menunggu approval admin.');
        }
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
        --yellow-50:#FFFBEB;
        --yellow-100:#FEF3C7;
        --yellow-400:#FBBF24;
        --amber-600:#D97706;
    }

    .riwayat-wrap {
        font-family: 'DM Sans', 'Segoe UI', sans-serif;
    }

    /* ── Alert ── */
    .riwayat-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 20px;
        background: var(--green-50);
        color: var(--green-600);
        border: 1px solid var(--green-100);
    }

    /* ── Page Header ── */
    .riwayat-page-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }
    .riwayat-page-icon {
        width: 42px; height: 42px;
        background: var(--blue-50);
        border: 1.5px solid var(--blue-100);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: var(--blue-500);
        font-size: 1rem;
        flex-shrink: 0;
    }
    .riwayat-page-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
    }
    .riwayat-page-sub {
        font-size: 0.75rem;
        color: var(--gray-400);
        margin: 0;
    }

    /* ── Filter pills ── */
    .filter-row {
        display: flex;
        gap: 7px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .filter-pill {
        padding: 7px 16px;
        border-radius: 100px;
        border: 1.5px solid var(--blue-100);
        background: var(--white);
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--gray-500);
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .filter-pill:hover {
        border-color: var(--blue-300);
        color: var(--blue-600);
        background: var(--blue-50);
    }
    .filter-pill.active {
        background: var(--blue-600);
        border-color: var(--blue-600);
        color: var(--white);
        box-shadow: 0 3px 10px rgba(37,99,235,0.22);
    }

    /* ── Borrow Card ── */
    .borrow-card {
        background: var(--white);
        border: 1.5px solid var(--blue-100);
        border-radius: 18px;
        padding: 18px 20px;
        margin-bottom: 14px;
        display: flex;
        gap: 18px;
        align-items: flex-start;
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        position: relative;
    }
    .borrow-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(37,99,235,0.09);
        border-color: var(--blue-200);
    }

    /* Cover */
    .borrow-cover {
        width: 80px;
        height: 112px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        background: var(--blue-50);
        box-shadow: 0 4px 12px rgba(37,99,235,0.12);
    }
    .borrow-cover img {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
    }
    .borrow-cover-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: var(--blue-200);
        font-size: 1.4rem;
    }

    /* Status badge — top right */
    .borrow-status {
        position: absolute;
        top: 16px; right: 16px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .borrow-status .sdot {
        width: 6px; height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .borrow-status.menunggu  { background: var(--yellow-50);  color: var(--amber-600); border: 1px solid var(--yellow-100); }
    .borrow-status.menunggu .sdot  { background: var(--amber-600); }
    .borrow-status.dipinjam  { background: var(--blue-50);    color: var(--blue-600);  border: 1px solid var(--blue-100); }
    .borrow-status.dipinjam .sdot  { background: var(--blue-500); }
    .borrow-status.dikembalikan { background: var(--green-50); color: var(--green-600); border: 1px solid var(--green-100); }
    .borrow-status.dikembalikan .sdot { background: var(--green-600); }
    .borrow-status.ditolak   { background: var(--red-50);     color: var(--red-500);   border: 1px solid var(--red-100); }
    .borrow-status.ditolak .sdot   { background: var(--red-500); }
    .borrow-status.pengembalian_menunggu { background: var(--yellow-50); color: var(--amber-600); border: 1px solid var(--yellow-100); }
    .borrow-status.pengembalian_menunggu .sdot { background: var(--amber-600); }
    .borrow-status.pengembalian_ditolak { background: var(--red-50); color: var(--red-500); border: 1px solid var(--red-100); }
    .borrow-status.pengembalian_ditolak .sdot { background: var(--red-500); }

    /* Detail section */
    .borrow-detail { flex: 1; min-width: 0; padding-right: 70px; }

    .borrow-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0 0 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .borrow-author {
        font-size: 0.78rem;
        color: var(--gray-400);
        margin-bottom: 12px;
    }

    /* Date grid */
    .date-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 16px;
        margin-bottom: 10px;
    }
    .date-item { display: flex; flex-direction: column; gap: 2px; }
    .date-label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: var(--gray-300);
    }
    .date-value {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--gray-600);
    }
    .date-value.empty { color: var(--gray-300); }

    /* Denda */
    .denda-row {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 100px;
        margin-bottom: 10px;
    }
    .denda-row.ada   { background: var(--red-50);   color: var(--red-500);   border: 1px solid var(--red-100); }
    .denda-row.tidak { background: var(--green-50); color: var(--green-600); border: 1px solid var(--green-100); }

    /* Action buttons */
    .borrow-actions { display: flex; gap: 7px; flex-wrap: wrap; margin-top: 12px; }
    .borrow-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 9px;
        font-size: 0.76rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: opacity 0.15s, transform 0.12s;
    }
    .borrow-btn:hover { opacity: 0.85; transform: translateY(-1px); }
    .borrow-btn.primary {
        background: #2563EB !important;
        color: #ffffff !important;
        box-shadow: 0 3px 10px rgba(37,99,235,0.25);
        border: none;
    }
    .borrow-btn.warning {
        background: #2563EB !important;
        color: #ffffff !important;
        border: none;
        box-shadow: 0 3px 10px rgba(37,99,235,0.2);
    }

    /* ── Empty state ── */
    .riwayat-empty {
        background: var(--white);
        border: 1.5px solid var(--blue-100);
        border-radius: 18px;
        padding: 56px 20px;
        text-align: center;
    }
    .riwayat-empty-icon {
        width: 64px; height: 64px;
        background: var(--blue-50);
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px;
        font-size: 1.5rem;
        color: var(--blue-200);
    }
    .riwayat-empty p {
        font-size: 0.9rem;
        color: var(--gray-400);
        margin: 0;
    }

    /* ── Pagination ── */
    .riwayat-wrap .pagination {
        margin-top: 20px;
        gap: 4px;
        justify-content: center;
    }
    .riwayat-wrap .page-link {
        border-radius: 8px !important;
        border: 1.5px solid var(--blue-100);
        color: var(--blue-600);
        font-size: 0.82rem;
        font-weight: 600;
        padding: 6px 12px;
        transition: all 0.15s;
    }
    .riwayat-wrap .page-link:hover {
        background: var(--blue-50);
        border-color: var(--blue-300);
        color: var(--blue-700);
    }
    .riwayat-wrap .page-item.active .page-link {
        background: var(--blue-600);
        border-color: var(--blue-600);
        color: white;
        box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    }
    .riwayat-wrap .page-item.disabled .page-link {
        color: var(--gray-300);
        border-color: var(--gray-100);
        background: var(--gray-50);
    }

    @media (max-width: 576px) {
        .borrow-detail { padding-right: 0; }
        .borrow-status { position: static; margin-bottom: 8px; display: inline-flex; }
        .borrow-card { flex-direction: column; }
        .date-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="riwayat-wrap">

    {{-- ── Alert ── --}}
    @if(session()->has('message'))
        <div class="riwayat-alert">
            <i class="fas fa-circle-check"></i> {{ session('message') }}
        </div>
    @endif

    {{-- ── Page Header ── --}}
    <div class="riwayat-page-header">
        <div class="riwayat-page-icon">
            <i class="fas fa-clock-rotate-left"></i>
        </div>
        <div>
            <p class="riwayat-page-title">Riwayat Peminjaman</p>
            <p class="riwayat-page-sub">Pantau semua aktivitas peminjaman buku Anda</p>
        </div>
    </div>

    {{-- ── Filter ── --}}
    <div class="filter-row">
        <button wire:click="$set('filterStatus','')"            class="filter-pill {{ $filterStatus=='' ? 'active' : '' }}">Semua</button>
        <button wire:click="$set('filterStatus','menunggu')"    class="filter-pill {{ $filterStatus=='menunggu' ? 'active' : '' }}">Menunggu</button>
        <button wire:click="$set('filterStatus','dipinjam')"    class="filter-pill {{ $filterStatus=='dipinjam' ? 'active' : '' }}">Dipinjam</button>
        <button wire:click="$set('filterStatus','pengembalian_menunggu')" class="filter-pill {{ $filterStatus=='pengembalian_menunggu' ? 'active' : '' }}">Menunggu Approval</button>
        <button wire:click="$set('filterStatus','dikembalikan')" class="filter-pill {{ $filterStatus=='dikembalikan' ? 'active' : '' }}">Dikembalikan</button>
        <button wire:click="$set('filterStatus','ditolak')"     class="filter-pill {{ $filterStatus=='ditolak' ? 'active' : '' }}">Ditolak</button>
        <button wire:click="$set('filterStatus','pengembalian_ditolak')" class="filter-pill {{ $filterStatus=='pengembalian_ditolak' ? 'active' : '' }}">Pengembalian Ditolak</button>
    </div>

    {{-- ── List ── --}}
    @if($this->riwayat->count())

        @foreach($this->riwayat as $pinjam)
        <div class="borrow-card">

            {{-- Status badge --}}
            <span class="borrow-status {{ $pinjam->status }}">
                <span class="sdot"></span>
                @switch($pinjam->status)
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
                        {{ ucfirst($pinjam->status) }}
                @endswitch
            </span>

            {{-- Cover --}}
            <div class="borrow-cover">
                @if($pinjam->buku && $pinjam->buku->foto)
                    <img src="{{ Storage::url($pinjam->buku->foto) }}" alt="{{ $pinjam->buku->judul }}">
                @else
                    <div class="borrow-cover-placeholder">
                        <i class="fas fa-book-open"></i>
                    </div>
                @endif
            </div>

            {{-- Detail --}}
            <div class="borrow-detail">
                <p class="borrow-title">{{ $pinjam->buku->judul ?? '-' }}</p>
                <p class="borrow-author">{{ $pinjam->buku->pengarang ?? '' }}</p>

                <div class="date-grid">
                    <div class="date-item">
                        <span class="date-label"><i class="fas fa-calendar-plus" style="margin-right:3px;"></i>Tgl. Pinjam</span>
                        <span class="date-value">
                            {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }}
                        </span>
                    </div>

                    <div class="date-item">
                        <span class="date-label"><i class="fas fa-calendar-clock" style="margin-right:3px;"></i>Est. Kembali</span>
                        <span class="date-value {{ !$pinjam->perkiraan_kembali || $pinjam->status === 'ditolak' ? 'empty' : '' }}">
                           @if(in_array($pinjam->status, ['dipinjam','dikembalikan','pengembalian_ditolak','pengembalian_menunggu']))
    {{ \Carbon\Carbon::parse($pinjam->perkiraan_kembali)->format('d M Y') }}
@else
    —
@endif
                        </span>
                    </div>

                    <div class="date-item">
                        <span class="date-label"><i class="fas fa-calendar-check" style="margin-right:3px;"></i>Tgl. Kembali</span>
                        <span class="date-value {{ !in_array($pinjam->status, ['dikembalikan']) ? 'empty' : '' }}">
                            @if($pinjam->status === 'dikembalikan')
                                {{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d M Y') }}
                            @else
                                —
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Denda --}}
                @if($pinjam->status === 'dikembalikan')
                    @if($pinjam->denda > 0)
                        <div class="denda-row ada">
                            <i class="fas fa-circle-exclamation"></i>
                            Denda: Rp {{ number_format($pinjam->denda, 0, ',', '.') }}
                        </div>
                    @else
                        <div class="denda-row tidak">
                            <i class="fas fa-circle-check"></i>
                            Tidak ada denda
                        </div>
                    @endif
                @endif

                {{-- Actions --}}
                <div class="borrow-actions">
                    @if($pinjam->status === 'dipinjam' || $pinjam->status === 'pengembalian_ditolak')
                        <button wire:click="kembalikan({{ $pinjam->id }})" class="borrow-btn primary">
                            <i class="fas fa-rotate-left"></i> Kembalikan
                        </button>
                        <a href="{{ route('pinjam.print', $pinjam->id) }}"
                           target="_blank"
                           class="borrow-btn"
                           style="background:#EFF6FF !important;color:#2563EB !important;border:1.5px solid #DBEAFE;text-decoration:none;">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                    @endif

                    @if($pinjam->status === 'dikembalikan')
                        <a href="{{ route('ulasan.show', $pinjam->buku->id) }}" class="borrow-btn warning">
                            <i class="fas fa-star"></i> Tulis Ulasan
                        </a>
                    @endif
                </div>

            </div>
        </div>
        @endforeach

        {{-- Pagination --}}
        <div>{{ $this->riwayat->links() }}</div>

    @else

        <div class="riwayat-empty">
            <div class="riwayat-empty-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <p>
                Belum ada buku yang
                <strong>{{ $filterStatus ? strtolower($filterStatus) : 'dipinjam' }}</strong>.
            </p>
        </div>

    @endif

</div>