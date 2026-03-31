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
        --yellow-400:#FBBF24;
        --yellow-50: #FFFBEB;
        --amber-600: #D97706;
    }

    /* ── Overlay ── */
    .popup-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        backdrop-filter: blur(6px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        padding: 20px;
        animation: overlayIn 0.2s ease;
    }

    @keyframes overlayIn {
        from { opacity: 0; }
        to   { opacity: 1; }
    }

    /* ── Modal box ── */
    .popup-box {
        background: var(--white);
        width: 100%;
        max-width: 960px;
        border-radius: 24px;
        position: relative;
        max-height: 90vh;
        overflow-y: auto;
        animation: popIn 0.25s cubic-bezier(0.34,1.56,0.64,1);
        box-shadow: 0 24px 60px rgba(37,99,235,0.15), 0 8px 24px rgba(0,0,0,0.08);
        scrollbar-width: thin;
        scrollbar-color: var(--blue-100) transparent;
    }
    .popup-box::-webkit-scrollbar { width: 5px; }
    .popup-box::-webkit-scrollbar-thumb { background: var(--blue-100); border-radius: 10px; }

    @keyframes popIn {
        from { transform: scale(0.92) translateY(12px); opacity: 0; }
        to   { transform: scale(1) translateY(0); opacity: 1; }
    }

    /* ── Close button ── */
    .popup-close {
        position: absolute;
        top: 18px; right: 18px;
        width: 36px; height: 36px;
        background: var(--gray-100);
        border: none;
        border-radius: 10px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: var(--gray-500);
        font-size: 0.95rem;
        transition: background 0.15s, color 0.15s;
        z-index: 10;
    }
    .popup-close:hover { background: var(--blue-50); color: var(--blue-600); }

    /* ── Inner layout ── */
    .popup-inner {
        display: flex;
        gap: 0;
        min-height: 480px;
    }

    /* ── LEFT: Cover ── */
    .popup-left {
        width: 260px;
        flex-shrink: 0;
        background: linear-gradient(160deg, var(--blue-50) 0%, var(--blue-100) 100%);
        border-radius: 24px 0 0 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 36px 24px 28px;
        gap: 20px;
    }

    .cover-img-wrap {
        width: 160px;
        height: 230px;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 28px rgba(37,99,235,0.2);
        background: var(--blue-100);
        flex-shrink: 0;
    }
    .cover-img-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
    }
    .cover-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: var(--blue-200);
        font-size: 2.5rem;
    }

    /* Action buttons */
    .popup-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
    }

    .act-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        border-radius: 12px;
        font-size: 0.83rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        border: none;
        transition: opacity 0.15s, transform 0.12s, box-shadow 0.15s;
        width: 100%;
    }
    .act-btn:hover { opacity: 0.88; transform: translateY(-1px); }

    .act-btn.pinjam {
        background: var(--blue-600);
        color: white;
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
    }
    .act-btn.favorite-add {
        background: var(--white);
        color: var(--gray-600);
        border: 1.5px solid var(--gray-200);
    }
    .act-btn.favorite-remove {
        background: var(--red-50);
        color: var(--red-500);
        border: 1.5px solid var(--red-100);
    }
    .act-btn.keranjang {
        background: var(--green-50);
        color: var(--green-600);
        border: 1.5px solid var(--green-100);
    }
    .act-btn.ulasan {
        background: var(--yellow-50);
        color: var(--amber-600);
        border: 1.5px solid #FEF3C7;
    }

    /* ── RIGHT: Detail ── */
    .popup-right {
        flex: 1;
        padding: 36px 36px 32px 32px;
        display: flex;
        flex-direction: column;
        gap: 0;
        overflow: hidden;
    }

    .popup-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1.3;
        margin-bottom: 12px;
        padding-right: 36px;
    }

    /* Rating row */
    .rating-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    .rating-score {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--gray-900);
    }
    .stars-row { display: flex; gap: 2px; }
    .star { font-size: 0.9rem; color: var(--gray-200); }
    .star.filled { color: var(--yellow-400); }
    .review-count {
        font-size: 0.78rem;
        color: var(--gray-400);
        font-weight: 500;
    }

    /* Meta divider */
    .popup-divider {
        height: 1px;
        background: var(--gray-100);
        margin: 0 0 18px;
    }

    /* Meta grid */
    .meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 20px;
        margin-bottom: 20px;
    }
    .meta-item { display: flex; flex-direction: column; gap: 2px; }
    .meta-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--gray-400);
    }
    .meta-value {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--gray-700);
    }
    .stok-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: var(--green-50);
        color: var(--green-600);
        font-size: 0.78rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 100px;
        border: 1px solid var(--green-100);
    }

    /* Sinopsis */
    .sinopsis-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--blue-500);
        margin-bottom: 6px;
    }
    .sinopsis-text {
        font-size: 0.87rem;
        color: var(--gray-500);
        line-height: 1.7;
        margin-bottom: 20px;
    }

    /* Reviews */
    .reviews-section { margin-top: 4px; }
    .reviews-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .reviews-title {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--gray-400);
    }
    .reviews-link {
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--blue-500);
        text-decoration: none;
        transition: color 0.15s;
    }
    .reviews-link:hover { color: var(--blue-700); }

    .review-item {
        background: var(--gray-50);
        border: 1px solid var(--gray-100);
        border-radius: 12px;
        padding: 12px 14px;
        margin-bottom: 8px;
    }
    .review-top {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
    }
    .reviewer-avatar {
        width: 28px; height: 28px;
        background: var(--blue-100);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        color: var(--blue-600);
        font-size: 0.72rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    .reviewer-name {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--gray-700);
    }
    .review-stars { display: flex; gap: 1px; }
    .review-star { font-size: 0.7rem; color: var(--gray-200); }
    .review-star.filled { color: var(--yellow-400); }
    .review-time {
        font-size: 0.7rem;
        color: var(--gray-300);
        margin-left: auto;
    }
    .review-text {
        font-size: 0.82rem;
        color: var(--gray-500);
        line-height: 1.5;
        margin: 0;
    }

    .no-reviews {
        background: var(--gray-50);
        border: 1px solid var(--gray-100);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        color: var(--gray-300);
        font-size: 0.85rem;
    }

    /* ── Responsive ── */
    @media (max-width: 680px) {
        .popup-inner { flex-direction: column; }
        .popup-left {
            width: 100%;
            border-radius: 24px 24px 0 0;
            padding: 28px 24px 20px;
            flex-direction: row;
            justify-content: flex-start;
            align-items: flex-start;
            gap: 20px;
        }
        .cover-img-wrap { width: 100px; height: 144px; }
        .popup-actions { flex-direction: row; flex-wrap: wrap; }
        .act-btn { width: auto; flex: 1; min-width: 80px; font-size: 0.75rem; }
        .popup-right { padding: 20px; }
        .popup-title { font-size: 1.2rem; padding-right: 0; }
        .meta-grid { grid-template-columns: 1fr; }
    }
</style>

@if($showModal && $selectedBuku)

@php
    $avgRating    = $selectedBuku->ulasans()->avg('rating') ?? 0;
    $totalReviews = $selectedBuku->ulasans()->count();
@endphp

<div class="popup-overlay" wire:click.self="closeModal">
    <div class="popup-box">

        {{-- Close --}}
        <button class="popup-close" wire:click="closeModal">
            <i class="fas fa-xmark"></i>
        </button>

        <div class="popup-inner">

            {{-- ── LEFT ── --}}
            <div class="popup-left">

                {{-- Cover --}}
                <div class="cover-img-wrap">
                    @if($selectedBuku->foto)
                        <img src="{{ asset('storage/'.$selectedBuku->foto) }}" alt="{{ $selectedBuku->judul }}">
                    @else
                        <div class="cover-placeholder">
                            <i class="fas fa-book-open"></i>
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="popup-actions">
                    <a href="{{ route('pinjam.create', $selectedBuku->id) }}" class="act-btn pinjam">
                        <i class="fas fa-bookmark"></i> Pinjam Buku
                    </a>

                    @auth
                        <button wire:click="toggleFavorite({{ $selectedBuku->id }})"
                            class="act-btn {{ $this->isFavorited ? 'favorite-remove' : 'favorite-add' }}">
                            @if($this->isFavorited)
                                <i class="fas fa-heart"></i> Hapus Favorit
                            @else
                                <i class="far fa-heart"></i> Favorit
                            @endif
                        </button>

                        <button wire:click="storeCart({{ $selectedBuku->id }})" class="act-btn keranjang">
                            <i class="fas fa-cart-plus"></i> Keranjang
                        </button>
                    @endauth

                    <a href="{{ route('ulasan.show', $selectedBuku->id) }}" class="act-btn ulasan">
                        <i class="fas fa-pen"></i> Tulis Ulasan
                    </a>
                </div>

            </div>

            {{-- ── RIGHT ── --}}
            <div class="popup-right">

                <h2 class="popup-title">{{ $selectedBuku->judul }}</h2>

                {{-- Rating --}}
                <div class="rating-row">
                    <span class="rating-score">{{ number_format($avgRating, 1) }}</span>
                    <div class="stars-row">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= round($avgRating) ? 'filled' : '' }}">★</span>
                        @endfor
                    </div>
                    <span class="review-count">{{ $totalReviews }} ulasan</span>
                </div>

                <div class="popup-divider"></div>

                {{-- Meta --}}
                <div class="meta-grid">
                    <div class="meta-item">
                        <span class="meta-label">Pengarang</span>
                        <span class="meta-value">{{ $selectedBuku->pengarang }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Penerbit</span>
                        <span class="meta-value">{{ $selectedBuku->penerbit }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Tahun Terbit</span>
                        <span class="meta-value">{{ $selectedBuku->tahun_terbit }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Stok</span>
                        <span class="meta-value">
                            <span class="stok-pill">
                                <i class="fas fa-circle" style="font-size:0.45rem;"></i>
                                {{ $selectedBuku->stok }} tersedia
                            </span>
                        </span>
                    </div>
                </div>

                <div class="popup-divider"></div>

                {{-- Sinopsis --}}
                @if($selectedBuku->sinopsis)
                    <p class="sinopsis-label">Sinopsis</p>
                    <p class="sinopsis-text">{{ $selectedBuku->sinopsis }}</p>
                @endif

                {{-- Reviews --}}
                <div class="reviews-section">
                    <div class="reviews-header">
                        <span class="reviews-title">Ulasan Terbaru</span>
                        @if($totalReviews > 0)
                            <a href="{{ route('ulasan.show', $selectedBuku->id) }}" class="reviews-link">
                                Lihat semua <i class="fas fa-arrow-right" style="font-size:0.7rem;"></i>
                            </a>
                        @endif
                    </div>

                    @if($selectedBuku->ulasans->count())
                        @foreach($selectedBuku->ulasans->sortByDesc('created_at')->take(3) as $review)
                        <div class="review-item">
                            <div class="review-top">
                                <div class="reviewer-avatar">
                                    {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="reviewer-name">{{ $review->user->name }}</span>
                                <div class="review-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="review-star {{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                                    @endfor
                                </div>
                                <span class="review-time">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if($review->ulasan)
                                <p class="review-text">{{ \Illuminate\Support\Str::limit($review->ulasan, 120) }}</p>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="no-reviews">
                            <i class="far fa-comment-dots" style="font-size:1.2rem;margin-bottom:6px;display:block;"></i>
                            Belum ada ulasan untuk buku ini.
                        </div>
                    @endif
                </div>

            </div>{{-- end right --}}

        </div>{{-- end inner --}}

    </div>{{-- end box --}}
</div>{{-- end overlay --}}

@endif