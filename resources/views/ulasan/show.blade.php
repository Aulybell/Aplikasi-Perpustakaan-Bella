@extends('layout.template')
@section('title', $buku->judul . ' — Ulasan Pembaca')

@section('content')
@php
    $old_rating = old('rating', $userReview->rating ?? 0);
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap');

/* ─── RESET & BASE ─────────────────────────────────── */
.ul-page *, .ul-page *::before, .ul-page *::after { box-sizing: border-box; }
.ul-page {
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: #111827;
    padding-bottom: 60px;
}

/* ─── TOAST ────────────────────────────────────────── */
.ul-toast {
    display: flex; align-items: center; gap: 14px;
    padding: 16px 20px; border-radius: 16px;
    margin-bottom: 28px;
    animation: ul-pop 0.4s cubic-bezier(.34,1.56,.64,1) both;
    font-size: 0.88rem; font-weight: 600;
}
.ul-toast.success { background:#ECFDF5; border:1.5px solid #6EE7B7; color:#065F46; }
.ul-toast.error   { background:#FEF2F2; border:1.5px solid #FCA5A5; color:#991B1B; }
.ul-toast-icon {
    width:32px; height:32px; border-radius:10px;
    display:flex; align-items:center; justify-content:center; font-size:0.85rem; flex-shrink:0;
}
.ul-toast.success .ul-toast-icon { background:#D1FAE5; color:#059669; }
.ul-toast.error   .ul-toast-icon { background:#FEE2E2; color:#DC2626; }
@keyframes ul-pop { from{opacity:0;transform:translateY(-14px) scale(.97)} to{opacity:1;transform:none} }

/* ─── LAYOUT ───────────────────────────────────────── */
.ul-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 28px;
    align-items: start;
}
@media(max-width:900px){ .ul-grid{ grid-template-columns:1fr; } }

/* ─── BOOK SIDEBAR ─────────────────────────────────── */
.ul-book-sticky { position: sticky; top: 80px; }
.ul-book-card {
    background: #fff;
    border: 1.5px solid #E5E7EB;
    border-radius: 24px; overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
}
.ul-book-cover {
    width:100%; aspect-ratio:2/3;
    background: linear-gradient(135deg,#EFF6FF,#DBEAFE);
    overflow:hidden; position:relative;
}
.ul-book-cover img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .4s; }
.ul-book-card:hover .ul-book-cover img { transform:scale(1.04); }
.ul-book-cover-placeholder {
    width:100%; height:100%; display:flex; flex-direction:column;
    align-items:center; justify-content:center; gap:10px;
    color:#BFDBFE; font-size:3rem;
}
.ul-book-body { padding:20px 20px 22px; }
.ul-book-title {
    font-family:'Instrument Serif', Georgia, serif;
    font-size:1.08rem; font-weight:400; color:#111827;
    margin:0 0 14px; line-height:1.4;
}
.ul-book-meta { display:flex; flex-direction:column; gap:7px; margin-bottom:16px; }
.ul-book-meta-row { display:flex; gap:8px; font-size:0.78rem; }
.ul-book-meta-key { color:#9CA3AF; font-weight:600; min-width:64px; }
.ul-book-meta-val { color:#374151; }
.ul-kategori-pill {
    display:inline-block; padding:3px 10px;
    background:#EFF6FF; color:#2563EB;
    border-radius:100px; font-size:0.7rem; font-weight:700;
}
.ul-divider { border:none; border-top:1px solid #F3F4F6; margin:14px 0; }
.ul-sinopsis {
    font-size:0.78rem; color:#6B7280; line-height:1.75;
    display:-webkit-box; -webkit-line-clamp:5; -webkit-box-orient:vertical; overflow:hidden;
    margin-bottom:16px;
}
.ul-btn-pinjam {
    display:flex; align-items:center; justify-content:center; gap:8px;
    width:100%; padding:12px; border-radius:14px;
    background:#1E40AF; color:#fff;
    font-family:inherit; font-size:0.85rem; font-weight:700;
    text-decoration:none; border:none; cursor:pointer;
    box-shadow:0 4px 16px rgba(30,64,175,0.25);
    transition:background .2s, transform .15s;
}
.ul-btn-pinjam:hover { background:#1D4ED8; color:#fff; transform:translateY(-2px); }

/* ─── RIGHT COLUMN ─────────────────────────────────── */

/* Score panel */
.ul-score-panel {
    background:#111827; border-radius:24px;
    padding:28px; margin-bottom:20px; position:relative; overflow:hidden;
}
.ul-score-panel::before {
    content:''; position:absolute; top:-80px; right:-80px;
    width:260px; height:260px;
    background:radial-gradient(circle,rgba(59,130,246,.3) 0%,transparent 65%);
    pointer-events:none;
}
.ul-score-inner { display:flex; gap:24px; align-items:center; flex-wrap:wrap; }
.ul-score-big {
    font-family:'Instrument Serif',serif;
    font-size:5rem; color:#fff; line-height:1; letter-spacing:-3px;
}
.ul-score-big sub { font-size:1.8rem; color:rgba(255,255,255,.3); letter-spacing:0; vertical-align:baseline; }
.ul-score-detail { flex:1; min-width:140px; }
.ul-score-stars { display:flex; gap:4px; margin-bottom:5px; }
.ul-score-stars span { font-size:1.2rem; }
.ul-score-stars .on  { color:#FBBF24; }
.ul-score-stars .off { color:rgba(255,255,255,.15); }
.ul-score-count { font-size:0.72rem; color:rgba(255,255,255,.4); letter-spacing:.5px; text-transform:uppercase; }
.ul-bars { flex:1; min-width:180px; }
.ul-bar-row { display:flex; align-items:center; gap:8px; margin-bottom:8px; }
.ul-bar-lbl { font-size:0.7rem; color:rgba(255,255,255,.4); width:12px; }
.ul-bar-track { flex:1; height:5px; background:rgba(255,255,255,.1); border-radius:100px; overflow:hidden; }
.ul-bar-fill { height:100%; background:linear-gradient(90deg,#FBBF24,#FCD34D); border-radius:100px; }
.ul-bar-n { font-size:0.68rem; color:rgba(255,255,255,.3); width:16px; text-align:right; }

/* Section label */
.ul-section-label {
    display:flex; align-items:center; gap:12px; margin-bottom:16px;
}
.ul-section-label-text {
    font-size:0.62rem; font-weight:800; letter-spacing:2.5px;
    text-transform:uppercase; color:#9CA3AF; white-space:nowrap;
}
.ul-section-label-line { flex:1; height:1px; background:#F3F4F6; }

/* Existing review box */
.ul-my-review {
    display:flex; gap:14px; align-items:flex-start;
    background:#F0F9FF; border:1.5px solid #BAE6FD;
    border-radius:18px; padding:18px; margin-bottom:16px;
}
.ul-my-review-icon {
    width:38px; height:38px; background:#0284C7;
    border-radius:11px; display:flex; align-items:center; justify-content:center;
    color:#fff; font-size:0.8rem; flex-shrink:0;
}
.ul-my-review-body { flex:1; }
.ul-my-review-tag { font-size:0.6rem; font-weight:800; letter-spacing:2px; text-transform:uppercase; color:#0284C7; margin-bottom:6px; }
.ul-my-review-stars { margin-bottom:6px; }
.ul-my-review-stars span { font-size:1rem; }
.ul-my-review-text {
    font-family:'Instrument Serif',serif; font-style:italic;
    font-size:0.9rem; color:#1E3A5F; line-height:1.7; margin-bottom:12px;
}

/* ─── FORM CARD ────────────────────────────────────── */
.ul-form-card {
    background:#fff; border:1.5px solid #E5E7EB;
    border-radius:20px; padding:24px;
    margin-bottom:20px;
    box-shadow:0 2px 12px rgba(0,0,0,0.04);
}
.ul-form-title {
    font-size:0.93rem; font-weight:800; color:#111827;
    margin:0 0 20px; display:flex; align-items:center; gap:8px;
}
.ul-form-title-icon {
    width:30px; height:30px; background:#FEF3C7; border-radius:8px;
    display:flex; align-items:center; justify-content:center; font-size:0.8rem;
}

/* Star picker */
.ul-star-row { display:flex; gap:8px; margin-bottom:8px; }
.ul-star {
    width:50px; height:50px; border-radius:14px;
    border:2px solid #E5E7EB; background:#F9FAFB;
    font-size:1.5rem; cursor:pointer; color:#D1D5DB;
    display:flex; align-items:center; justify-content:center;
    transition:all .2s cubic-bezier(.34,1.56,.64,1);
    user-select:none;
}
.ul-star:hover         { transform:scale(1.18) translateY(-4px); border-color:#FCD34D; color:#FBBF24; background:#FFFBEB; box-shadow:0 8px 20px rgba(251,191,36,.2); }
.ul-star.selected      { border-color:#F59E0B; color:#F59E0B; background:#FFFBEB; transform:scale(1.1); box-shadow:0 4px 14px rgba(245,158,11,.2); }
.ul-star-desc { font-size:0.78rem; color:#9CA3AF; margin-bottom:18px; min-height:20px; transition:all .2s; font-style:italic; }

/* Hidden input for rating */
#rating-value { display:none; }

.ul-field-label {
    display:block; font-size:0.65rem; font-weight:800;
    letter-spacing:1.5px; text-transform:uppercase; color:#9CA3AF; margin-bottom:8px;
}
.ul-textarea {
    width:100%; padding:14px 16px;
    border:1.5px solid #E5E7EB; border-radius:14px;
    font-family:'Plus Jakarta Sans',sans-serif; font-size:0.88rem;
    color:#111827; background:#F9FAFB; resize:vertical; min-height:110px;
    outline:none; line-height:1.7;
    transition:all .2s;
}
.ul-textarea:focus { border-color:#3B82F6; background:#fff; box-shadow:0 0 0 4px rgba(59,130,246,.08); }
.ul-textarea::placeholder { color:#D1D5DB; font-style:italic; }
.ul-err { font-size:0.72rem; color:#EF4444; margin-top:4px; display:flex; align-items:center; gap:5px; }

.ul-form-footer { display:flex; align-items:center; gap:12px; margin-top:18px; }
.ul-btn-submit {
    display:inline-flex; align-items:center; gap:8px;
    padding:13px 26px; border-radius:14px;
    background:#111827; color:#fff;
    font-family:inherit; font-size:0.83rem; font-weight:700;
    border:none; cursor:pointer;
    box-shadow:0 4px 20px rgba(17,24,39,.2);
    transition:all .2s; letter-spacing:.3px;
}
.ul-btn-submit:hover { background:#1D4ED8; box-shadow:0 6px 24px rgba(29,78,216,.35); transform:translateY(-2px); }
.ul-btn-del {
    display:inline-flex; align-items:center; gap:6px;
    padding:6px 14px; border-radius:10px;
    font-family:inherit; font-size:0.75rem; font-weight:700;
    background:#FEF2F2; color:#DC2626;
    border:1.5px solid #FECACA; cursor:pointer;
    transition:all .15s;
}
.ul-btn-del:hover { background:#DC2626; color:#fff; border-color:#DC2626; }

/* Login prompt */
.ul-login-box {
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    padding:36px 20px; gap:12px;
    background:#F9FAFB; border:1.5px dashed #E5E7EB; border-radius:16px;
    color:#9CA3AF; font-size:0.87rem; text-align:center;
}
.ul-login-box a {
    display:inline-flex; align-items:center; gap:6px;
    padding:10px 22px; border-radius:12px;
    background:#111827; color:#fff;
    font-family:inherit; font-size:0.83rem; font-weight:700;
    text-decoration:none; transition:background .2s;
}
.ul-login-box a:hover { background:#1D4ED8; }

/* ─── REVIEW CARDS ─────────────────────────────────── */
.ul-review-card {
    background:#fff; border:1.5px solid #F3F4F6;
    border-radius:20px; padding:20px 22px;
    margin-bottom:14px;
    transition:box-shadow .2s, transform .2s, border-color .2s;
    animation:ul-fadein .4s ease both;
}
.ul-review-card:hover { box-shadow:0 8px 28px rgba(0,0,0,.07); transform:translateY(-2px); border-color:#E5E7EB; }
@keyframes ul-fadein { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:none} }
.ul-review-top { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px; gap:10px; }
.ul-reviewer { display:flex; align-items:center; gap:12px; }
.ul-avatar {
    width:44px; height:44px; border-radius:14px;
    background:linear-gradient(135deg,#3B82F6,#7C3AED);
    color:#fff; display:flex; align-items:center; justify-content:center;
    font-size:1rem; font-weight:800; flex-shrink:0;
    letter-spacing:-.5px;
}
.ul-reviewer-name { font-size:0.9rem; font-weight:700; color:#111827; }
.ul-reviewer-time { font-size:0.7rem; color:#9CA3AF; margin-top:2px; }
.ul-review-stars-right { display:flex; gap:3px; margin-bottom:6px; justify-content:flex-end; }
.ul-review-stars-right span { font-size:0.9rem; }
.ul-review-stars-right .on  { color:#F59E0B; }
.ul-review-stars-right .off { color:#E5E7EB; }
.ul-review-body {
    font-family:'Instrument Serif',serif; font-style:italic;
    font-size:0.92rem; color:#374151; line-height:1.8;
    padding-top:14px; border-top:1px solid #F9FAFB;
    position:relative;
}
.ul-review-body::before {
    content:'"'; position:absolute; top:-10px; left:-4px;
    font-size:2.5rem; color:#E5E7EB; font-style:normal; line-height:1;
}

/* ─── EMPTY ────────────────────────────────────────── */
.ul-empty {
    text-align:center; padding:52px 20px;
    background:#fff; border:1.5px solid #F3F4F6; border-radius:20px;
    color:#D1D5DB;
}
.ul-empty-icon { font-size:2.8rem; margin-bottom:12px; }
.ul-empty-text { font-size:0.87rem; color:#9CA3AF; }

/* ─── PAGINATION ───────────────────────────────────── */
.ul-page-wrap .pagination { display:flex; gap:4px; justify-content:center; margin-top:20px; flex-wrap:wrap; }
.ul-page-wrap .page-link {
    border-radius:10px !important; border:1.5px solid #E5E7EB;
    color:#374151; font-size:0.8rem; font-weight:700; padding:7px 14px;
    font-family:'Plus Jakarta Sans',sans-serif; transition:all .15s;
}
.ul-page-wrap .page-link:hover { background:#F3F4F6; border-color:#D1D5DB; }
.ul-page-wrap .page-item.active .page-link { background:#111827 !important; border-color:#111827 !important; color:#fff !important; }
.ul-page-wrap .page-item.disabled .page-link { color:#E5E7EB; border-color:#F3F4F6; background:#FAFAFA; }
</style>

<div class="ul-page">

    {{-- ── Toast ── --}}
    @if(session('success'))
        <div class="ul-toast success">
            <div class="ul-toast-icon"><i class="fas fa-check"></i></div>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="ul-toast error">
            <div class="ul-toast-icon"><i class="fas fa-triangle-exclamation"></i></div>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="ul-grid">

        {{-- ══════════════════════════ --}}
        {{-- SIDEBAR BUKU             --}}
        {{-- ══════════════════════════ --}}
        <div>
            <div class="ul-book-sticky">
                <div class="ul-book-card">
                    <div class="ul-book-cover">
                        @if($buku->foto)
                            <img src="{{ Storage::url($buku->foto) }}" alt="{{ $buku->judul }}">
                        @else
                            <div class="ul-book-cover-placeholder">
                                <i class="fas fa-book-open"></i>
                            </div>
                        @endif
                    </div>
                    <div class="ul-book-body">
                        <h1 class="ul-book-title">{{ $buku->judul }}</h1>
                        <div class="ul-book-meta">
                            <div class="ul-book-meta-row">
                                <span class="ul-book-meta-key">Pengarang</span>
                                <span class="ul-book-meta-val">{{ $buku->pengarang }}</span>
                            </div>
                            <div class="ul-book-meta-row">
                                <span class="ul-book-meta-key">Penerbit</span>
                                <span class="ul-book-meta-val">{{ $buku->penerbit }}</span>
                            </div>
                            <div class="ul-book-meta-row">
                                <span class="ul-book-meta-key">Tahun</span>
                                <span class="ul-book-meta-val">{{ $buku->tahun_terbit }}</span>
                            </div>
                            <div class="ul-book-meta-row">
                                <span class="ul-book-meta-key">Kategori</span>
                                <span class="ul-kategori-pill">
                                    {{ optional($buku->kategori)->nama_kategori ?? '-' }}
                                </span>
                            </div>
                        </div>
                        @if($buku->sinopsis)
                            <hr class="ul-divider">
                            <p class="ul-sinopsis">{{ $buku->sinopsis }}</p>
                        @endif
                        <a href="{{ route('pinjam.create', $buku->id) }}" class="ul-btn-pinjam">
                            <i class="fas fa-bookmark"></i> Pinjam Buku
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════ --}}
        {{-- KOLOM KANAN              --}}
        {{-- ══════════════════════════ --}}
        <div>

            {{-- Score Panel --}}
            <div class="ul-score-panel">
                <div class="ul-score-inner">
                    <div class="ul-score-big">
                        {{ number_format($averageRating, 1) }}<sub>/5</sub>
                    </div>
                    <div class="ul-score-detail">
                        <div class="ul-score-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= round($averageRating) ? 'on' : 'off' }}">★</span>
                            @endfor
                        </div>
                        <div class="ul-score-count">{{ $totalReviews }} ulasan pembaca</div>
                    </div>
                    <div class="ul-bars">
                        @for($r = 5; $r >= 1; $r--)
                        <div class="ul-bar-row">
                            <span class="ul-bar-lbl">{{ $r }}</span>
                            <div class="ul-bar-track">
                                <div class="ul-bar-fill"
                                     style="width:{{ $totalReviews > 0 ? round($ratingCounts[$r]/$totalReviews*100) : 0 }}%">
                                </div>
                            </div>
                            <span class="ul-bar-n">{{ $ratingCounts[$r] }}</span>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ── Form Ulasan ── --}}
            @if(auth()->check() && auth()->user()->role === 'user')
            <div class="ul-section-label">
                <div class="ul-section-label-line"></div>
                <div class="ul-section-label-text">Tulis Ulasan</div>
                <div class="ul-section-label-line"></div>
            </div>

            @auth
                {{-- Ulasan yang sudah ada --}}
                @if($userReview)
                    <div class="ul-my-review">
                        <div class="ul-my-review-icon"><i class="fas fa-pen"></i></div>
                        <div class="ul-my-review-body">
                            <div class="ul-my-review-tag">Ulasan Anda</div>
                            <div class="ul-my-review-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span style="color:{{ $i <= $userReview->rating ? '#F59E0B' : '#E5E7EB' }}">★</span>
                                @endfor
                            </div>
                            @if($userReview->ulasan)
                                <div class="ul-my-review-text">"{{ $userReview->ulasan }}"</div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Form --}}
<div class="ul-form-card">
    <div class="ul-form-title">
        <div class="ul-form-title-icon">⭐</div>
        {{ $userReview ? 'Edit Ulasan Anda' : 'Beri Ulasan' }}
    </div>

    {{-- FORM SIMPAN / UPDATE --}}
    <form action="{{ route('ulasan.store', $buku->id) }}" method="POST">
        @csrf
        <input type="hidden" name="rating" id="rating-value" value="{{ $old_rating }}">

        {{-- Star Picker --}}
        <label class="ul-field-label">Rating Bintang</label>
        <div class="ul-star-row" id="star-picker">
            @for($i = 1; $i <= 5; $i++)
                <div class="ul-star {{ $old_rating >= $i ? 'selected' : '' }}"
                     data-val="{{ $i }}">★</div>
            @endfor
        </div>

        <div class="ul-star-desc" id="star-desc">
            @if($old_rating == 1) 😞 Kurang memuaskan
            @elseif($old_rating == 2) 😐 Biasa saja
            @elseif($old_rating == 3) 🙂 Lumayan bagus
            @elseif($old_rating == 4) 😊 Bagus sekali!
            @elseif($old_rating == 5) 🤩 Luar biasa!
            @else Klik bintang untuk memberi rating
            @endif
        </div>

        @error('rating')
            <p class="ul-err"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
        @enderror

        {{-- Ulasan Text --}}
        <label class="ul-field-label" style="margin-top:4px;">
            Ulasan 
            <span style="font-weight:400;text-transform:none;letter-spacing:0;opacity:.6;">
                (opsional)
            </span>
        </label>

        <textarea name="ulasan" class="ul-textarea"
                  placeholder="Ceritakan pengalaman membaca Anda...">{{ old('ulasan', $userReview->ulasan ?? '') }}</textarea>

        @error('ulasan')
            <p class="ul-err"><i class="fas fa-circle-exclamation"></i> {{ $message }}</p>
        @enderror

        {{-- BUTTON SIMPAN --}}
        <div class="ul-form-footer">
            <button type="submit" class="ul-btn-submit">
                <i class="fas fa-paper-plane"></i>
                {{ $userReview ? 'Perbarui Ulasan' : 'Kirim Ulasan' }}
            </button>
        </div>
    </form>

    {{-- FORM DELETE (DIPISAH, TIDAK DI DALAM FORM ATAS) --}}
    @if($userReview)
        <form action="{{ route('ulasan.delete', $userReview->id) }}"
              method="POST" style="margin-top:10px;">
            @csrf
            @method('DELETE')

            <button type="submit" class="ul-btn-del"
                    onclick="return confirm('Hapus ulasan ini?')">
                <i class="fas fa-trash"></i> Hapus Ulasan
            </button>
        </form>
    @endif
</div>

<script>
(function () {
    const stars    = document.querySelectorAll('#star-picker .ul-star');
    const input    = document.getElementById('rating-value');
    const descEl   = document.getElementById('star-desc');
    const descs    = ['', '😞 Kurang memuaskan', '😐 Biasa saja', '🙂 Lumayan bagus', '😊 Bagus sekali!', '🤩 Luar biasa!'];

    function pick(val) {
        input.value = val;
        stars.forEach((s, i) => {
            s.classList.toggle('selected', i < val);
        });
        descEl.textContent = descs[val] || 'Klik bintang untuk memberi rating';
    }

    stars.forEach((star, idx) => {
        star.addEventListener('click', () => pick(idx + 1));
        star.addEventListener('mouseenter', () => {
            stars.forEach((s, i) => s.style.color = i <= idx ? '#F59E0B' : '');
        });
    });

    document.getElementById('star-picker').addEventListener('mouseleave', () => {
        const cur = parseInt(input.value) || 0;
        stars.forEach((s, i) => s.style.color = i < cur ? '#F59E0B' : '');
    });
})();
</script>
@endauth
@endif
@endsection