<!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.html" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fas fa-book-open me-2"></i>ReadMe</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="{{ asset('assets/img/user.png') }}" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{ Auth()->user()->name }}</h6>
                        <span>{{ Auth()->user()->role }}</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                        <a href="{{ route('users') }}" class="nav-item nav-link"><i class="fas fa-users me-2"></i>Users</a>
                        <a href="{{ route('penjaga') }}" class="nav-item nav-link"><i class="fas fa-user-tie me-2"></i>Petugas</a>
                        <a href="{{ route('buku') }}" class="nav-item nav-link"><i class="fas fa-pen me-2"></i>Kelola Buku</a>
                        <a href="{{ route('kategori.index') }}" class="nav-item nav-link"><i class="fas fa-tags me-2"></i>Kategori</a>
                        <a href="{{ route('pinjam') }}" class="nav-item nav-link"><i class="fas fa-bookmark me-2"></i>Peminjaman</a>
                        <a href="{{ route('laporan') }}" class="nav-item nav-link"><i class="fas fa-file me-2"></i>Laporan</a>
                    @elseif(Auth::check() && Auth::user()->role === 'petugas')
                        <a href="{{ route('dashboard') }}" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                        <div class="nav-item dropdown">
                        </div>
                        <a href="{{ route('buku') }}" class="nav-item nav-link"><i class="fas fa-pen me-2"></i>Kelola Buku</a>
                        <a href="{{ route('pinjam') }}" class="nav-item nav-link"><i class="fas fa-bookmark me-2"></i>Peminjaman</a>
                        <a href="{{ route('laporan') }}" class="nav-item nav-link"><i class="fas fa-file me-2"></i>Laporan</a>
                    @elseif(Auth::check() && Auth::user()->role === 'user')
                        <a href="{{ route('koleksi') }}" class="nav-item nav-link"><i class="fas fa-book me-2"></i>Koleksi</a>
                        <a href="{{ route('favorites') }}" class="nav-item nav-link"><i class="fas fa-heart me-2"></i>Favorites</a>
                        <a href="{{ route('riwayat') }}" class="nav-item nav-link"><i class="fas fa-th-list me-2"></i>Riwayat</a>
                        <a href="{{ route('keranjang.index') }}" class="nav-item nav-link"><i class="fas fa-shopping-cart me-2"></i>Keranjang</a>
                        <a href="{{ route('notifikasi') }}" class="nav-item nav-link"><i class="fas fa-bell me-2"></i>Notifikasi</a>
                    @else
                        
                        <a href="{{ route('users') }}" class="nav-item nav-link"><i class="fas fa-users me-2"></i>Users</a>
                        <a href="{{ route('penjaga') }}" class="nav-item nav-link"><i class="fas fa-user-tie me-2"></i>Petugas</a>
                        <a href="{{ route('buku') }}" class="nav-item nav-link"><i class="fas fa-pen me-2"></i>Kelola Buku</a>
                        <a href="{{ route('kategori.index') }}" class="nav-item nav-link"><i class="fas fa-tags me-2"></i>Kategori</a>
                        <a href="{{ route('laporan') }}" class="nav-item nav-link"><i class="fas fa-file me-2"></i>Laporan</a>
                        <a href="{{ route('pinjam') }}" class="nav-item nav-link"><i class="fas fa-bookmark me-2"></i>Pinjam</a>
                        <a href="{{ route('koleksi') }}" class="nav-item nav-link"><i class="fas fa-book me-2"></i>Buku</a>
                        <a href="{{ route('riwayat') }}" class="nav-item nav-link"><i class="fas fa-th-list me-2"></i>Riwayat</a>
                        <a href="{{ route('favorites') }}" class="nav-item nav-link"><i class="fas fa-heart me-2"></i>Favorites</a>
                    @endif
                    </div>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->