@extends('layout.template')
@section('title', 'Notifikasi - ReadMe')

@section('content')
<style>
    .notif-page {
        font-family: 'DM Sans', 'Segoe UI', sans-serif;
        min-height: 100vh;
        background: linear-gradient(160deg, #EFF6FF 0%, #FFFFFF 60%);
        padding: 48px 20px 60px;
    }

    .notif-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .notif-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .notif-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 8px;
    }

    .notif-header p {
        color: #64748B;
        font-size: 0.9rem;
    }

    .notif-list-container {
        background: #FFFFFF;
        border: 1.5px solid #DBEAFE;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(37,99,235,0.08);
        overflow: hidden;
    }

    .notif-list {
        padding: 0;
    }

    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid #F1F5F9;
        transition: background 0.2s;
    }

    .notif-item:last-child {
        border-bottom: none;
    }

    .notif-item:hover {
        background: #F8FAFC;
    }

    .notif-item-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .notif-item-icon.approved {
        background: #F0FDF4;
    }

    .notif-item-icon.rejected {
        background: #FEF2F2;
    }

    .notif-item-icon.returned {
        background: #EFF6FF;
    }

    .notif-item-info {
        flex: 1;
        min-width: 0;
    }

    .notif-item-message {
        font-size: 0.95rem;
        font-weight: 500;
        color: #0F172A;
        margin-bottom: 4px;
        line-height: 1.4;
    }

    .notif-item-message strong {
        font-weight: 700;
    }

    .notif-item-meta {
        font-size: 0.8rem;
        color: #94A3B8;
        margin: 0;
    }

    .notif-item-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .notif-status-approved {
        background: #F0FDF4;
        color: #16A34A;
        border: 1px solid #DCFCE7;
    }

    .notif-status-rejected {
        background: #FEF2F2;
        color: #EF4444;
        border: 1px solid #FECACA;
    }

    .notif-status-returned {
        background: #EFF6FF;
        color: #2563EB;
        border: 1px solid #DBEAFE;
    }

    .notif-empty {
        text-align: center;
        padding: 40px 24px;
        color: #94A3B8;
    }

    .notif-empty-icon {
        font-size: 3rem;
        margin-bottom: 16px;
    }

    .notif-empty h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #64748B;
        margin-bottom: 8px;
    }

    .notif-empty p {
        font-size: 0.9rem;
        margin: 0;
    }

    @media (max-width: 640px) {
        .notif-page {
            padding: 24px 16px 40px;
        }

        .notif-header h1 {
            font-size: 1.5rem;
        }

        .notif-item {
            flex-direction: row;
            align-items: flex-start;
            gap: 12px;
        }
    }
</style>

<div class="notif-page">
    <div class="notif-container">
        <div class="notif-header">
            <h1>🔔 Notifikasi</h1>
            <p>Informasi terkini tentang pengajuan peminjaman dan pengembalian buku Anda</p>
        </div>

        {{-- Daftar Notifikasi Gabungan --}}
        <div class="notif-list-container">
            @php
                $allNotifications = collect();
                
                $disetujui->each(function($pinjam) use ($allNotifications) {
                    $allNotifications->push([
                        'type' => 'peminjaman_disetujui',
                        'data' => $pinjam,
                        'timestamp' => $pinjam->updated_at
                    ]);
                });
                
                $ditolak->each(function($pinjam) use ($allNotifications) {
                    $allNotifications->push([
                        'type' => 'peminjaman_ditolak',
                        'data' => $pinjam,
                        'timestamp' => $pinjam->updated_at
                    ]);
                });
                
                $pengembalianDisetujui->each(function($pinjam) use ($allNotifications) {
                    $allNotifications->push([
                        'type' => 'pengembalian_disetujui',
                        'data' => $pinjam,
                        'timestamp' => $pinjam->updated_at
                    ]);
                });
                
                $pengembalianDitolak->each(function($pinjam) use ($allNotifications) {
                    $allNotifications->push([
                        'type' => 'pengembalian_ditolak',
                        'data' => $pinjam,
                        'timestamp' => $pinjam->updated_at
                    ]);
                });
                
                $allNotifications = $allNotifications->sortByDesc('timestamp');
            @endphp
            
            @forelse($allNotifications as $notif)
                <div class="notif-item">
                    @if($notif['type'] == 'peminjaman_disetujui')
                        <div class="notif-item-icon approved">✅</div>
                        <div class="notif-item-info">
                            <div class="notif-item-message">
                                Pengajuan peminjaman <strong>{{ $notif['data']->buku->judul }}</strong> telah diterima
                            </div>
                            <p class="notif-item-meta">{{ $notif['data']->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    @elseif($notif['type'] == 'peminjaman_ditolak')
                        <div class="notif-item-icon rejected">❌</div>
                        <div class="notif-item-info">
                            <div class="notif-item-message">
                                Pengajuan peminjaman <strong>{{ $notif['data']->buku->judul }}</strong> ditolak. Silahkan ajukan lagi besok
                            </div>
                            <p class="notif-item-meta">{{ $notif['data']->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    @elseif($notif['type'] == 'pengembalian_disetujui')
                        <div class="notif-item-icon returned">✅</div>
                        <div class="notif-item-info">
                            <div class="notif-item-message">
                                Pengembalian buku <strong>{{ $notif['data']->buku->judul }}</strong> telah diterima
                            </div>
                            <p class="notif-item-meta">{{ $notif['data']->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    @elseif($notif['type'] == 'pengembalian_ditolak')
                        <div class="notif-item-icon rejected">❌</div>
                        <div class="notif-item-info">
                            <div class="notif-item-message">
                                Pengembalian buku <strong>{{ $notif['data']->buku->judul }}</strong> ditolak. Silahkan ajukan kembali besok
                            </div>
                            <p class="notif-item-meta">{{ $notif['data']->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="notif-empty">
                    <div class="notif-empty-icon">🔔</div>
                    <h3>Belum ada notifikasi</h3>
                    <p>Semua notifikasi akan muncul di sini</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection