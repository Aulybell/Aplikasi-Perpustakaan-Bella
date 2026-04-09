<div class="ulasan-wrap">
    @if(session('success'))
        <div class="ulasan-alert success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="ulasan-alert error">
            <i class="fas fa-exclamation-triangle"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="ulasan-card">
        <div class="ulasan-header">
            <div class="ulasan-header-left">
                <div class="ulasan-header-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div>
                    <h1 class="ulasan-header-title">Kelola Ulasan Buku</h1>
                    <p class="ulasan-header-sub">Pantau dan kelola ulasan dari peminjam</p>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="ulasan-filter">
            <h2 class="ulasan-filter-title">Filter Ulasan</h2>
            <div class="ulasan-filter-grid">
                <div class="ulasan-filter-group">
                    <label class="ulasan-filter-label">Cari Judul Buku</label>
                    <input type="text" wire:model.live="search" placeholder="Masukkan judul buku..."
                           class="ulasan-filter-input">
                </div>
                <div class="ulasan-filter-group">
                    <label class="ulasan-filter-label">Rating</label>
                    <select wire:model.live="ratingFilter" class="ulasan-filter-select">
                        <option value="">Semua Rating</option>
                        <option value="5">5 Bintang</option>
                        <option value="4">4 Bintang</option>
                        <option value="3">3 Bintang</option>
                        <option value="2">2 Bintang</option>
                        <option value="1">1 Bintang</option>
                    </select>
                </div>
                <div class="ulasan-filter-group">
                    <label class="ulasan-filter-label">Urutkan</label>
                    <select wire:model.live="sortBy" class="ulasan-filter-select">
                        <option value="latest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="rating_high">Rating Tertinggi</option>
                        <option value="rating_low">Rating Terendah</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Daftar Ulasan -->
        <div class="ulasan-content">
            <div class="ulasan-content-header">
                <h2 class="ulasan-content-title">Daftar Ulasan ({{ $ulasans->total() }} total)</h2>
            </div>

            @if($ulasans->count() > 0)
                <div class="ulasan-list">
                    @foreach($ulasans as $ulasan)
                        <div class="ulasan-item">
                            <div class="ulasan-item-main">
                                <div class="ulasan-user">
                                    <div class="ulasan-user-avatar">
                                        {{ strtoupper(substr($ulasan->user->name, 0, 1)) }}
                                    </div>
                                    <div class="ulasan-user-info">
                                        <h3 class="ulasan-user-name">{{ $ulasan->user->name }}</h3>
                                        <p class="ulasan-user-date">{{ $ulasan->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="ulasan-rating">
                                    <div class="ulasan-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="ulasan-star {{ $i <= $ulasan->rating ? 'active' : '' }}">★</span>
                                        @endfor
                                        <span class="ulasan-rating-text">{{ $ulasan->rating }}/5</span>
                                    </div>
                                </div>
                                @if($ulasan->ulasan)
                                    <p class="ulasan-text">"{{ $ulasan->ulasan }}"</p>
                                @endif
                            </div>
                            <div class="ulasan-item-book">
                                <h4 class="ulasan-book-title">{{ $ulasan->buku->judul }}</h4>
                                <p class="ulasan-book-author">{{ $ulasan->buku->pengarang }}</p>
                                <p class="ulasan-book-category">{{ optional($ulasan->buku->kategori)->nama_kategori }}</p>
                            </div>
                                @if(auth()->user()->role === 'admin')
                                    <button wire:click="deleteUlasan({{ $ulasan->id }})"
                                            wire:confirm="Apakah Anda yakin ingin menghapus ulasan ini?"
                                            class="btn-act delete">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="ulasan-footer">
                    {{ $ulasans->links() }}
                </div>
            @else
                <div class="ulasan-empty">
                    <div class="ulasan-empty-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Tidak ada ulasan ditemukan</h3>
                    <p>Coba ubah filter pencarian atau tambahkan ulasan baru.</p>
                </div>
            @endif
        </div>
    </div>
</div>
