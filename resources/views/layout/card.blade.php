 <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                                <i class="fa fa-book fa-3x text-primary"></i>
                                <div class="ms-3">
                                    <p class="mb-2">Buku</p>
                                    <h6 class="mb-0">{{ $totalBuku ?? 0 }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-bookmark fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Pinjam</p>
                                <h6 class="mb-0">{{ $totalPinjam ?? 0 }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-3">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                                <i class="fa fa-retweet fa-3x text-primary"></i>
                                <div class="ms-3">
                                    <p class="mb-2">Kembali</p>
                                    <h6 class="mb-0">{{ $totalKembali ?? 0 }}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                   
            <!-- Sale & Revenue End -->