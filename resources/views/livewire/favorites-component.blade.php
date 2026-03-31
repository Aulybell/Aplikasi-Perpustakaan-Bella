<?php
use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

new class extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function getFavoritesProperty()
    {
        if (!Auth::check()) return collect();
        return Auth::user()->favorites()->paginate(10);
    }

    public function removeFavorite($bukuId)
    {
        if (Auth::check()) {
            Auth::user()->favorites()->detach($bukuId);
            session()->flash('message', 'Buku dihapus dari favorit.');
            $this->resetPage();
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
        --red-50:   #FFF1F2;
        --red-100:  #FFE4E6;
        --red-500:  #EF4444;
        --green-50: #F0FDF4;
        --green-100:#DCFCE7;
        --green-600:#16A34A;
    }

    .fav-wrap {
        font-family: 'DM Sans', 'Segoe UI', sans-serif;
    }

    /* ── Alert ── */
    .fav-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 22px;
        background: var(--green-50);
        color: var(--green-600);
        border: 1px solid var(--green-100);
    }

    /* ── Card container ── */
    .fav-card {
        background: var(--white);
        border: 1.5px solid var(--blue-100);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(37,99,235,0.06);
    }

    /* ── Header ── */
    .fav-header {
        background: var(--blue-50);
        border-bottom: 1.5px solid var(--blue-100);
        padding: 20px 26px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .fav-header-icon {
        width: 40px; height: 40px;
        background: var(--white);
        border: 1.5px solid var(--blue-100);
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: var(--blue-500);
        font-size: 1rem;
        flex-shrink: 0;
    }
    .fav-header-title {
        font-size: 0.97rem;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
    }
    .fav-header-sub {
        font-size: 0.75rem;
        color: var(--gray-400);
        margin: 0;
    }

    /* ── Body ── */
    .fav-body {
        padding: 28px 26px 24px;
    }

    /* ── Book Grid ── */
    .fav-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 20px;
    }

    /* ── Book Item ── */
    .fav-item {
        display: flex;
        flex-direction: column;
        background: var(--white);
        border: 1.5px solid var(--blue-100);
        border-radius: 16px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    }
    .fav-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 28px rgba(37,99,235,0.11);
        border-color: var(--blue-300);
    }

    .fav-cover {
        position: relative;
        width: 100%;
        height: 210px;
        background: var(--blue-50);
        overflow: hidden;
        flex-shrink: 0;
    }
    .fav-cover img {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s;
    }
    .fav-item:hover .fav-cover img {
        transform: scale(1.04);
    }
    .cover-no-img {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: var(--blue-200);
        font-size: 2rem;
    }

    /* Heart badge on cover */
    .heart-badge {
        position: absolute;
        top: 10px; right: 10px;
        width: 28px; height: 28px;
        background: rgba(255,255,255,0.9);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--red-500);
        font-size: 0.75rem;
        backdrop-filter: blur(4px);
    }

    .fav-info {
        padding: 12px 14px 14px;
        display: flex;
        flex-direction: column;
        flex: 1;
        gap: 4px;
    }
    .fav-title {
        font-size: 0.83rem;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1.35;
        margin: 0 0 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .fav-sinopsis {
        font-size: 0.75rem;
        color: var(--gray-400);
        line-height: 1.5;
        flex: 1;
        margin: 0 0 12px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .fav-btns {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .fav-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 10px;
        border-radius: 10px;
        font-size: 0.76rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: opacity 0.15s, transform 0.12s;
        width: 100%;
        text-align: center;
    }
    .fav-btn:hover { opacity: 0.85; transform: translateY(-1px); }

    .fav-btn.pinjam {
        background: #2563EB !important;
        color: #ffffff !important;
        border: none;
        box-shadow: 0 3px 10px rgba(37,99,235,0.2);
    }
    .fav-btn.remove {
        background: var(--red-50);
        color: var(--red-500);
        border: 1.5px solid var(--red-100);
    }
    .fav-btn.ulasan {
        background: var(--blue-600);
        color: white;
        box-shadow: 0 3px 10px rgba(37,99,235,0.2);
    }

    /* ── Empty state ── */
    .fav-empty {
        text-align: center;
        padding: 60px 20px;
    }
    .fav-empty-icon {
        width: 68px; height: 68px;
        background: var(--blue-50);
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.6rem;
        color: var(--blue-200);
    }
    .fav-empty p {
        color: var(--gray-400);
        font-size: 0.9rem;
        margin: 0;
    }

    /* ── Pagination ── */
    .fav-wrap .pagination {
        margin-top: 24px;
        gap: 4px;
        justify-content: center;
    }
    .fav-wrap .page-link {
        border-radius: 8px !important;
        border: 1.5px solid var(--blue-100);
        color: var(--blue-600);
        font-size: 0.82rem;
        font-weight: 600;
        padding: 6px 12px;
        transition: all 0.15s;
    }
    .fav-wrap .page-link:hover {
        background: var(--blue-50);
        border-color: var(--blue-300);
        color: var(--blue-700);
    }
    .fav-wrap .page-item.active .page-link {
        background: var(--blue-600);
        border-color: var(--blue-600);
        color: white;
        box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    }
    .fav-wrap .page-item.disabled .page-link {
        color: var(--gray-300);
        border-color: var(--gray-100);
        background: var(--gray-50);
    }
</style>

<div class="fav-wrap">

    {{-- ── Alert ── --}}
    @if(session()->has('message'))
        <div class="fav-alert">
            <i class="fas fa-circle-check"></i>
            {{ session('message') }}
        </div>
    @endif

    <div class="fav-card">

        {{-- ── Header ── --}}
        <div class="fav-header">
            <div class="fav-header-icon">
                <i class="fas fa-heart"></i>
            </div>
            <div>
                <p class="fav-header-title">Buku Favorit Saya</p>
                <p class="fav-header-sub">Koleksi buku yang kamu tandai sebagai favorit</p>
            </div>
        </div>

        {{-- ── Body ── --}}
        <div class="fav-body">

            @if($this->favorites->count())

                <div class="fav-grid">
                    @foreach($this->favorites as $buku)
                    <div class="fav-item">

                        {{-- Cover --}}
                        <div class="fav-cover">
                            @if($buku->foto)
                                <img src="{{ asset('storage/'.$buku->foto) }}" alt="{{ $buku->judul }}">
                            @else
                                <div class="cover-no-img">
                                    <i class="fas fa-book-open"></i>
                                </div>
                            @endif
                            <div class="heart-badge">
                                <i class="fas fa-heart"></i>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="fav-info">
                            <p class="fav-title">{{ $buku->judul }}</p>
                            @if($buku->sinopsis)
                                <p class="fav-sinopsis">{{ $buku->sinopsis }}</p>
                            @endif

                            <div class="fav-btns">
                                <a href="{{ route('pinjam.create', $buku->id) }}" class="fav-btn pinjam">
                                    <i class="fas fa-book-bookmark"></i> Pinjam Buku
                                </a>
                                <button wire:click="removeFavorite({{ $buku->id }})" class="fav-btn remove">
                                    <i class="fas fa-heart-crack"></i> Hapus Favorit
                                </button>
                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div>{{ $this->favorites->links() }}</div>

            @else

                <div class="fav-empty">
                    <div class="fav-empty-icon">
                        <i class="far fa-heart"></i>
                    </div>
                    <p>Anda belum menandai buku favorit apapun.</p>
                </div>

            @endif

        </div>

    </div>

</div>