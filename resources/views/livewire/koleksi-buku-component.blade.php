<?php

use App\Models\Buku;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;
use App\Models\Kategori;
use Livewire\Component;

new class extends Component
{
    public $selectedBuku = null;
    public $showModal = false;

    public $search = '';
    public $filterKategori = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterKategori' => ['except' => '']
    ];

    public function updatedSearch()
    {
        $this->search = trim($this->search);
    }

    public function getBukusProperty()
    {
        return Buku::query()
            ->when($this->search !== '', function ($query) {
                $search = strtolower($this->search);
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(judul) LIKE ?', ["%{$search}%"])
                      ->orWhereRaw('LOWER(pengarang) LIKE ?', ["%{$search}%"]);
                });
            })
            ->when($this->filterKategori !== '', function ($query) {
                $query->where('kategori', $this->filterKategori);
            })
            ->get();
    }

    public function getKategorisProperty()
    {
        return Kategori::orderBy('nama_kategori')->get();
    }

    public function pilihBuku($id)
    {
        $this->selectedBuku = Buku::with(['ulasans.user','favoritedBy'])->findOrFail($id);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedBuku = null;
    }

    public function toggleFavorite($bukuId)
    {
        if (!Auth::check()) return redirect()->route('login');
        $user = Auth::user();
        $user->favorites()->toggle($bukuId);
        if ($this->selectedBuku && $this->selectedBuku->id == $bukuId) {
            $this->selectedBuku->load('favoritedBy');
        }
    }

    public function getIsFavoritedProperty()
    {
        if (!Auth::check() || !$this->selectedBuku) return false;
        return $this->selectedBuku->favoritedBy->contains(Auth::id());
    }

    public function storeCart($bukuId)
    {
        if (!Auth::check()) return redirect()->route('login');
        $existing = Keranjang::where('user_id', Auth::id())->where('buku_id', $bukuId)->first();
        if ($existing) {
            $existing->increment('quantity');
            session()->flash('message', 'Kuantitas buku ditambahkan ke keranjang.');
        } else {
            Keranjang::create(['user_id' => Auth::id(), 'buku_id' => $bukuId, 'quantity' => 1]);
            session()->flash('message', 'Buku ditambahkan ke keranjang.');
        }
        return redirect()->route('koleksi');
    }

    public function getCartCountProperty()
    {
        if (!Auth::check()) return 0;
        return Keranjang::where('user_id', Auth::id())->count();
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
        --green-600:#16A34A;
        --red-50:   #FFF1F2;
        --red-600:  #DC2626;
        --yellow-400: #FBBF24;
    }

    .koleksi-wrap {
        font-family: 'DM Sans', 'Segoe UI', sans-serif;
        background: var(--gray-50);
        min-height: 100vh;
        padding: 36px 20px 60px;
    }

    /* ── Alert ── */
    .koleksi-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 24px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    .koleksi-alert.success { background: var(--green-50); color: var(--green-600); border: 1px solid #DCFCE7; }
    .koleksi-alert.error   { background: var(--red-50);   color: var(--red-600);   border: 1px solid #FFE4E6; }

    /* ── Search bar ── */
    .search-wrap {
        max-width: 520px;
        margin: 0 auto 28px;
        position: relative;
    }
    .search-wrap i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-400);
        font-size: 0.9rem;
        pointer-events: none;
    }
    .search-input {
        width: 100%;
        padding: 13px 20px 13px 44px;
        border-radius: 14px;
        border: 1.5px solid var(--blue-100);
        background: var(--white);
        font-size: 0.9rem;
        color: var(--gray-900);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(37,99,235,0.05);
    }
    .search-input::placeholder { color: var(--gray-300); }
    .search-input:focus {
        border-color: var(--blue-400);
        box-shadow: 0 0 0 3px rgba(96,165,250,0.15);
    }

    /* ── Kategori tabs ── */
    .kategori-tabs {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 36px;
    }
    .tab-btn {
        background: var(--white);
        border: 1.5px solid var(--blue-100);
        color: var(--gray-500);
        border-radius: 100px;
        padding: 8px 20px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.18s;
        white-space: nowrap;
    }
    .tab-btn:hover {
        border-color: var(--blue-300);
        color: var(--blue-600);
        background: var(--blue-50);
    }
    .tab-btn.active {
        background: var(--blue-600);
        border-color: var(--blue-600);
        color: var(--white);
        box-shadow: 0 3px 10px rgba(37,99,235,0.25);
    }

    /* ── Book Grid ── */
    .book-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        justify-content: center;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* ── Book Card ── */
    .book-card {
        width: 170px;
        cursor: pointer;
        transition: transform 0.22s, box-shadow 0.22s;
        flex-shrink: 0;
    }
    .book-card:hover {
        transform: translateY(-6px);
    }
    .book-cover-wrap {
        position: relative;
        width: 170px;
        height: 248px;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(37,99,235,0.12);
        background: var(--blue-50);
    }
    .book-cover-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s;
    }
    .book-card:hover .book-cover-wrap img {
        transform: scale(1.04);
    }
    .cover-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(145deg, var(--blue-50), var(--blue-100));
        color: var(--blue-200);
    }
    .cover-placeholder i { font-size: 2.2rem; }

    /* Stok badge on cover */
    .stok-badge {
        position: absolute;
        top: 10px; right: 10px;
        background: rgba(255,255,255,0.92);
        color: var(--blue-600);
        font-size: 0.65rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 100px;
        backdrop-filter: blur(4px);
        border: 1px solid var(--blue-100);
    }

    .book-info {
        padding: 10px 2px 0;
    }
    .book-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1.35;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .book-author {
        font-size: 0.72rem;
        color: var(--gray-400);
        margin-bottom: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .book-rating {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .stars {
        display: flex;
        gap: 1px;
    }
    .star { color: var(--gray-200); font-size: 0.75rem; }
    .star.filled { color: var(--yellow-400); }
    .rating-val {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--gray-500);
    }

    /* ── Empty State ── */
    .not-found {
        width: 100%;
        text-align: center;
        padding: 70px 20px;
    }
    .not-found-icon {
        width: 68px; height: 68px;
        background: var(--blue-50);
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.6rem;
        color: var(--blue-200);
    }
    .not-found h5 {
        font-size: 0.95rem;
        color: var(--gray-400);
        font-weight: 500;
        margin: 0;
    }
    .not-found strong { color: var(--blue-500); }

    /* ── Section header ── */
    .section-header {
        text-align: center;
        margin-bottom: 28px;
    }
    .section-label {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: var(--blue-500);
        margin-bottom: 6px;
    }
    .section-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.7rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
    }

    @media (max-width: 576px) {
        .book-card { width: 145px; }
        .book-cover-wrap { width: 145px; height: 210px; }
        .search-input { font-size: 0.85rem; }
    }
</style>

<div class="koleksi-wrap">

    {{-- ── Alerts ── --}}
    @if(session()->has('message'))
        <div class="koleksi-alert success">
            <i class="fas fa-circle-check"></i> {{ session('message') }}
        </div>
    @endif
    @if(session()->has('error'))
        <div class="koleksi-alert error">
            <i class="fas fa-circle-exclamation"></i> {!! session('error') !!}
        </div>
    @endif

    {{-- ── Section Header ── --}}
    <div class="section-header">
        <p class="section-label">Perpustakaan</p>
        <h2 class="section-title">Koleksi Buku</h2>
    </div>

    {{-- ── Search ── --}}
    <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input
            wire:model.debounce.500ms="search"
            type="text"
            class="search-input"
            placeholder="Cari judul atau pengarang…"
            name="search"
            value="{{ $search }}">
    </div>

    {{-- ── Kategori Tabs (dynamic from DB) ── --}}
    <div class="kategori-tabs">
        <button wire:click="$set('filterKategori','')" class="tab-btn {{ $filterKategori == '' ? 'active' : '' }}">Semua</button>
        @foreach($this->kategoris as $kat)
            <button wire:click="$set('filterKategori','{{ $kat->nama_kategori }}')" class="tab-btn {{ $filterKategori == $kat->nama_kategori ? 'active' : '' }}">{{ $kat->nama_kategori }}</button>
        @endforeach
    </div>

    {{-- ── Book Grid ── --}}
    <div class="book-grid">

        @if($this->bukus->count() > 0)
            @foreach($this->bukus as $buku)
                @php
                    $avgRating  = $buku->ulasans()->avg('rating') ?? 0;
                    $ratingVal  = number_format($avgRating, 1);
                @endphp

                <div class="book-card" wire:click="pilihBuku({{ $buku->id }})">
                    <div class="book-cover-wrap">
                        @if($buku->foto)
                            <img src="{{ asset('storage/'.$buku->foto) }}" alt="{{ $buku->judul }}">
                        @else
                            <div class="cover-placeholder">
                                <i class="fas fa-book-open"></i>
                            </div>
                        @endif

                        @if(isset($buku->stok))
                            <span class="stok-badge">{{ $buku->stok }} stok</span>
                        @endif
                    </div>

                    <div class="book-info">
                        <p class="book-title">{{ $buku->judul }}</p>
                        <p class="book-author">{{ $buku->pengarang }}</p>
                        <div class="book-rating">
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= floor($ratingVal) ? 'filled' : '' }}">★</span>
                                @endfor
                            </div>
                            <span class="rating-val">{{ $ratingVal }}</span>
                        </div>
                    </div>
                </div>

            @endforeach

        @else
            <div class="not-found">
                <div class="not-found-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                @if($search != '')
                    <h5>Buku "<strong>{{ $search }}</strong>" tidak ditemukan</h5>
                @else
                    <h5>Belum ada data buku tersedia</h5>
                @endif
            </div>
        @endif

    </div>

    @include('koleksi.BookPopup')

</div>