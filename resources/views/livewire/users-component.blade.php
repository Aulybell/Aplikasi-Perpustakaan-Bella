<?php

use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\User;
use Livewire\Component;

new class extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public function getUsersProperty()
    {
        return User::whereIn('role', ['peminjam', 'user'])->paginate(10);
    }

    public function delete($id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                $name = $user->name;
                $user->delete();
                session()->flash('message', "User '$name' berhasil dihapus");
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

};
?>

<style>
    .users-wrap { font-family: 'DM Sans', 'Segoe UI', sans-serif; }

    /* ── Alert ── */
    .users-alert {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 18px; border-radius: 12px;
        font-size: 0.85rem; font-weight: 500; margin-bottom: 20px;
        background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7;
    }

    /* ── Card ── */
    .users-card {
        background: #FFFFFF;
        border: 1.5px solid #DBEAFE;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(37,99,235,0.06);
    }

    /* ── Header ── */
    .users-header {
        background: #EFF6FF;
        border-bottom: 1.5px solid #DBEAFE;
        padding: 20px 26px;
        display: flex; align-items: center; gap: 12px;
    }
    .users-header-icon {
        width: 40px; height: 40px; background: #FFFFFF;
        border: 1.5px solid #DBEAFE; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #3B82F6; font-size: 1rem; flex-shrink: 0;
    }
    .users-header-title { font-size: 0.97rem; font-weight: 700; color: #0F172A; margin: 0; }
    .users-header-sub   { font-size: 0.75rem; color: #94A3B8; margin: 0; }

    /* ── Table ── */
    .users-table-wrap { overflow-x: auto; }
    .users-table { width: 100%; border-collapse: collapse; font-size: 0.845rem; }
    .users-table thead tr { background: #F8FAFC; border-bottom: 1.5px solid #DBEAFE; }
    .users-table thead th {
        padding: 13px 18px; font-size: 0.7rem; font-weight: 700;
        letter-spacing: 0.8px; text-transform: uppercase;
        color: #3B82F6; white-space: nowrap;
    }
    .users-table tbody tr { border-bottom: 1px solid #F1F5F9; transition: background 0.15s; }
    .users-table tbody tr:last-child { border-bottom: none; }
    .users-table tbody tr:hover { background: #EFF6FF; }
    .users-table tbody td { padding: 14px 18px; color: #334155; vertical-align: middle; }

    .td-no { font-size: 0.74rem; font-weight: 700; color: #CBD5E1; text-align: center; width: 36px; }

    /* Avatar + name */
    .user-cell { display: flex; align-items: center; gap: 10px; }
    .user-avatar {
        width: 34px; height: 34px; border-radius: 10px;
        background: #DBEAFE; color: #2563EB;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.82rem; font-weight: 700; flex-shrink: 0;
    }
    .user-name  { font-weight: 700; color: #0F172A; }
    .user-email { font-size: 0.78rem; color: #94A3B8; }

    /* Role badge */
    .role-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 100px;
        font-size: 0.7rem; font-weight: 700;
        background: #EFF6FF; color: #2563EB; border: 1px solid #DBEAFE;
    }
    .role-dot { width: 5px; height: 5px; border-radius: 50%; background: #2563EB; }

    /* Delete button */
    .btn-delete {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 13px; border-radius: 8px;
        font-size: 0.75rem; font-weight: 600;
        background: #EF4444 !important; color: #ffffff !important;
        border: none; cursor: pointer; font-family: inherit;
        transition: opacity 0.15s, transform 0.12s;
    }
    .btn-delete:hover { opacity: 0.85; transform: translateY(-1px); }

    /* ── Pagination ── */
    .users-footer { padding: 16px 22px; border-top: 1px solid #F1F5F9; }
    .users-wrap .pagination { margin: 0; gap: 4px; justify-content: center; }
    .users-wrap .page-link {
        border-radius: 8px !important; border: 1.5px solid #DBEAFE;
        color: #2563EB; font-size: 0.82rem; font-weight: 600;
        padding: 6px 12px; transition: all 0.15s;
    }
    .users-wrap .page-link:hover { background: #EFF6FF; border-color: #93C5FD; }
    .users-wrap .page-item.active .page-link {
        background: #2563EB !important; border-color: #2563EB !important;
        color: white !important; box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    }
    .users-wrap .page-item.disabled .page-link {
        color: #CBD5E1; border-color: #F1F5F9; background: #F8FAFC;
    }

    /* ── Empty ── */
    .users-empty { padding: 52px 20px; text-align: center; }
    .users-empty-icon {
        width: 60px; height: 60px; background: #EFF6FF; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px; font-size: 1.4rem; color: #BFDBFE;
    }
    .users-empty p { color: #94A3B8; font-size: 0.88rem; margin: 0; }
</style>

<div class="users-wrap">

    @php $list = $this->users; @endphp

    {{-- ── Alert ── --}}
    @if(session()->has('message'))
        <div class="users-alert">
            <i class="fas fa-circle-check"></i> {{ session('message') }}
        </div>
    @endif

    <div class="users-card">

        {{-- ── Header ── --}}
        <div class="users-header">
            <div class="users-header-icon"><i class="fas fa-users"></i></div>
            <div>
                <p class="users-header-title">Daftar User</p>
                <p class="users-header-sub">Semua pengguna</p>
            </div>
        </div>

        {{-- ── Table ── --}}
        <div class="users-table-wrap">
            <table class="users-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">#</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($list as $data)
                    <tr>
                        <td class="td-no">
                            {{ ($list->currentPage() - 1) * $list->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($data->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="user-name">{{ $data->name }}</div>
                                    <div class="user-email">{{ $data->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="color:#64748B;font-size:0.83rem;max-width:200px;white-space:normal;">
                            {{ $data->alamat ?? '—' }}
                        </td>
                        <td>
                            <span class="role-badge">
                                <span class="role-dot"></span>
                                {{ ucfirst($data->role) }}
                            </span>
                        </td>
                        <td>
                            <button wire:click="delete({{ $data->id }})" 
                                    wire:confirm="Yakin hapus user '{{ $data->name }}'?"
                                    type="button" 
                                    class="btn-delete">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="users-empty">
                                <div class="users-empty-icon"><i class="fas fa-users"></i></div>
                                <p>Belum ada data user.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination --}}
        <div class="users-footer">
            {{ $list->links() }}
        </div>

    </div>
</div>
