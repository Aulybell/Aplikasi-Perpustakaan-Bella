<?php

use Livewire\WithPagination;
use Livewire\withoutUrlPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

new class extends Component
{
    use WithPagination, withoutUrlPagination;

    protected $paginationTheme = 'bootstrap';
    public $addPage, $editPage = false;
    public $name, $email, $password, $alamat, $role, $id;

    public function getPenjagasProperty()
    {
        return User::whereIn('role', ['petugas'])->paginate(10);
    }

    public function create()  { $this->addPage = true; }

    public function store()
    {
        $this->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'alamat'   => 'required',
            'role'     => 'required|in:admin,petugas',
        ],[
            'name.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'email.unique'      => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'alamat.required'   => 'Alamat wajib diisi.',
            'role.required'     => 'Role wajib diisi.',
            'role.in'           => 'Role harus Admin atau Petugas.',
        ]);

        User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'alamat'   => $this->alamat,
            'role'     => $this->role,
        ]);

        session()->flash('message', 'Penjaga berhasil ditambahkan.');
        $this->addPage = false;
        $this->reset();
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'Penjaga berhasil dihapus.');
        $this->reset();
    }

    public function edit($id)
    {
        $cari = User::find($id);
        $this->name   = $cari->name;
        $this->email  = $cari->email;
        $this->alamat = $cari->alamat;
        $this->role   = $cari->role;
        $this->id     = $cari->id;
        $this->editPage = true;
    }

    public function update()
    {
        $this->validate([
            'name'   => 'required',
            'email'  => 'required|email|unique:users,email,' . $this->id,
            'alamat' => 'required',
            'role'   => 'required|in:admin,petugas',
        ]);

        $cari = User::find($this->id);
        $data = ['name' => $this->name, 'email' => $this->email, 'alamat' => $this->alamat, 'role' => $this->role];
        if ($this->password) $data['password'] = Hash::make($this->password);
        $cari->update($data);

        session()->flash('message', 'Penjaga berhasil diupdate.');
        $this->reset();
    }
};
?>

<style>
    .penjaga-wrap { font-family: 'DM Sans', 'Segoe UI', sans-serif; }

    /* ── Alert ── */
    .pj-alert {
        display: flex; align-items: center; gap: 10px;
        padding: 12px 18px; border-radius: 12px;
        font-size: 0.85rem; font-weight: 500; margin-bottom: 20px;
        background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7;
    }

    /* ── Card ── */
    .pj-card {
        background: #FFFFFF;
        border: 1.5px solid #DBEAFE;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(37,99,235,0.06);
    }

    /* ── Header ── */
    .pj-header {
        background: #EFF6FF;
        border-bottom: 1.5px solid #DBEAFE;
        padding: 20px 26px;
        display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }
    .pj-header-left { display: flex; align-items: center; gap: 12px; }
    .pj-header-icon {
        width: 40px; height: 40px; background: #FFFFFF;
        border: 1.5px solid #DBEAFE; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #3B82F6; font-size: 1rem; flex-shrink: 0;
    }
    .pj-header-title { font-size: 0.97rem; font-weight: 700; color: #0F172A; margin: 0; }
    .pj-header-sub   { font-size: 0.75rem; color: #94A3B8; margin: 0; }

    .btn-tambah {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 11px;
        font-size: 0.82rem; font-weight: 700;
        background: #2563EB !important; color: #ffffff !important;
        border: none; cursor: pointer; font-family: inherit;
        box-shadow: 0 3px 10px rgba(37,99,235,0.25);
        transition: background 0.2s, transform 0.15s;
    }
    .btn-tambah:hover { background: #1D4ED8 !important; transform: translateY(-1px); }

    /* ── Table ── */
    .pj-table-wrap { overflow-x: auto; }
    .pj-table { width: 100%; border-collapse: collapse; font-size: 0.845rem; }
    .pj-table thead tr { background: #F8FAFC; border-bottom: 1.5px solid #DBEAFE; }
    .pj-table thead th {
        padding: 13px 18px; font-size: 0.7rem; font-weight: 700;
        letter-spacing: 0.8px; text-transform: uppercase; color: #3B82F6; white-space: nowrap;
    }
    .pj-table tbody tr { border-bottom: 1px solid #F1F5F9; transition: background 0.15s; }
    .pj-table tbody tr:last-child { border-bottom: none; }
    .pj-table tbody tr:hover { background: #EFF6FF; }
    .pj-table tbody td, .pj-table tbody th { padding: 14px 18px; color: #334155; vertical-align: middle; }

    .td-no { font-size: 0.74rem; font-weight: 700; color: #CBD5E1; text-align: center; width: 36px; }
    .td-name { font-weight: 700; color: #0F172A; }

    /* Avatar initials */
    .user-cell { display: flex; align-items: center; gap: 10px; }
    .user-avatar {
        width: 34px; height: 34px; border-radius: 10px;
        background: #DBEAFE; color: #2563EB;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.82rem; font-weight: 700; flex-shrink: 0;
    }

    /* Role badge */
    .role-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 100px;
        font-size: 0.7rem; font-weight: 700;
    }
    .role-badge.admin   { background: #EFF6FF; color: #2563EB; border: 1px solid #DBEAFE; }
    .role-badge.petugas { background: #F0FDF4; color: #16A34A; border: 1px solid #DCFCE7; }

    /* Action buttons */
    .act-wrap { display: flex; gap: 6px; }
    .btn-act {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 12px; border-radius: 8px;
        font-size: 0.75rem; font-weight: 600;
        border: none; cursor: pointer; font-family: inherit;
        transition: opacity 0.15s, transform 0.12s;
        white-space: nowrap;
    }
    .btn-act:hover { opacity: 0.85; transform: translateY(-1px); }
    .btn-act.edit   { background: #EFF6FF !important; color: #2563EB !important; border: 1.5px solid #DBEAFE; }
    .btn-act.delete { background: #EF4444 !important; color: #ffffff !important; }

    /* ── Footer / Pagination ── */
    .pj-footer { padding: 16px 22px; border-top: 1px solid #F1F5F9; }
    .penjaga-wrap .pagination { margin: 0; gap: 4px; justify-content: center; }
    .penjaga-wrap .page-link {
        border-radius: 8px !important; border: 1.5px solid #DBEAFE;
        color: #2563EB; font-size: 0.82rem; font-weight: 600;
        padding: 6px 12px; transition: all 0.15s;
    }
    .penjaga-wrap .page-link:hover { background: #EFF6FF; border-color: #93C5FD; color: #1D4ED8; }
    .penjaga-wrap .page-item.active .page-link {
        background: #2563EB !important; border-color: #2563EB !important;
        color: white !important; box-shadow: 0 2px 8px rgba(37,99,235,0.25);
    }
    .penjaga-wrap .page-item.disabled .page-link {
        color: #CBD5E1; border-color: #F1F5F9; background: #F8FAFC;
    }

    /* ── Empty ── */
    .pj-empty { padding: 52px 20px; text-align: center; }
    .pj-empty-icon {
        width: 60px; height: 60px; background: #EFF6FF; border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 14px; font-size: 1.4rem; color: #BFDBFE;
    }
    .pj-empty p { color: #94A3B8; font-size: 0.88rem; margin: 0; }

    /* ── Modal Overlay ── */
    .pj-modal-overlay {
        position: fixed; inset: 0;
        background: rgba(15,23,42,0.45);
        backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999; padding: 20px;
        animation: fadeIn 0.2s ease;
    }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .pj-modal {
        background: #FFFFFF;
        border: 1.5px solid #DBEAFE;
        border-radius: 20px;
        width: 100%; max-width: 480px;
        box-shadow: 0 20px 50px rgba(37,99,235,0.15);
        animation: popIn 0.22s cubic-bezier(0.34,1.56,0.64,1);
        overflow: hidden;
    }
    @keyframes popIn {
        from { transform: scale(0.93) translateY(10px); opacity: 0; }
        to   { transform: scale(1) translateY(0); opacity: 1; }
    }
    .pj-modal-header {
        background: #EFF6FF; border-bottom: 1.5px solid #DBEAFE;
        padding: 18px 24px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .pj-modal-title { font-size: 0.95rem; font-weight: 700; color: #0F172A; margin: 0; }
    .pj-modal-close {
        width: 32px; height: 32px; background: #FFFFFF;
        border: 1.5px solid #DBEAFE; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #94A3B8; font-size: 0.85rem;
        transition: background 0.15s, color 0.15s;
    }
    .pj-modal-close:hover { background: #DBEAFE; color: #2563EB; }

    .pj-modal-body { padding: 22px 24px 24px; }

    /* Form inside modal */
    .mfield { margin-bottom: 16px; }
    .mfield-label {
        display: block; font-size: 0.72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.6px;
        color: #64748B; margin-bottom: 6px;
    }
    .mfield-input, .mfield-select {
        width: 100%; padding: 10px 14px;
        border: 1.5px solid #DBEAFE; border-radius: 10px;
        font-size: 0.87rem; color: #0F172A;
        background: #FFFFFF; outline: none; font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .mfield-input:focus, .mfield-select:focus {
        border-color: #60A5FA;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.15);
    }
    .mfield-input::placeholder { color: #CBD5E1; }

    .mfield-hint { font-size: 0.71rem; color: #94A3B8; margin-top: 4px; }

    @error('name') .err-msg @enderror { font-size: 0.72rem; color: #EF4444; margin-top: 3px; }
    .err-msg { font-size: 0.72rem; color: #EF4444; margin-top: 3px; }

    .modal-actions { display: flex; gap: 8px; margin-top: 20px; }
    .btn-modal-save {
        flex: 1; padding: 11px;
        background: #2563EB !important; color: #ffffff !important;
        border: none; border-radius: 11px; font-size: 0.88rem; font-weight: 700;
        cursor: pointer; font-family: inherit;
        box-shadow: 0 3px 10px rgba(37,99,235,0.25);
        transition: background 0.2s, transform 0.15s;
        display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .btn-modal-save:hover { background: #1D4ED8 !important; transform: translateY(-1px); }
    .btn-modal-cancel {
        padding: 11px 20px; background: #F1F5F9 !important; color: #64748B !important;
        border: none; border-radius: 11px; font-size: 0.88rem; font-weight: 600;
        cursor: pointer; font-family: inherit; transition: background 0.15s;
    }
    .btn-modal-cancel:hover { background: #E2E8F0 !important; 
}
</style>

<div class="penjaga-wrap">

    {{-- ── Alert ── --}}
    @if(session()->has('message'))
        <div class="pj-alert">
            <i class="fas fa-circle-check"></i> {{ session('message') }}
        </div>
    @endif

    <div class="pj-card">

        {{-- ── Header ── --}}
        <div class="pj-header">
            <div class="pj-header-left">
                <div class="pj-header-icon"><i class="fas fa-user-shield"></i></div>
                <div>
                    <p class="pj-header-title">Daftar Petugas</p>
                    <p class="pj-header-sub">Kelola akun petugas perpustakaan</p>
                </div>
            </div>
            <button wire:click="create" class="btn-tambah">
                <i class="fas fa-plus"></i> Tambah Petugas
            </button>
        </div>

        {{-- ── Table ── --}}
        <div class="pj-table-wrap">
            <table class="pj-table">
                <thead>
                    <tr>
                        <th style="text-align:center;">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->penjagas as $data)
                    <tr>
                        <td class="td-no">
                            {{ ($this->penjagas->currentPage() - 1) * $this->penjagas->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($data->name, 0, 1)) }}
                                </div>
                                <span class="td-name">{{ $data->name }}</span>
                            </div>
                        </td>
                        <td style="color:#64748B;font-size:0.83rem;">{{ $data->email }}</td>
                        <td style="color:#64748B;font-size:0.83rem;max-width:160px;white-space:normal;">{{ $data->alamat }}</td>
                        <td>
                            <span class="role-badge {{ $data->role }}">
                                {{ ucfirst($data->role) }}
                            </span>
                        </td>
                        <td>
                            <div class="act-wrap">
                                <button wire:click="edit({{ $data->id }})" class="btn-act edit">
                                    <i class="fas fa-pen"></i> Edit
                                </button>
                                <button wire:click="destroy({{ $data->id }})" wire:confirm="Hapus petugas ini?" class="btn-act delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="pj-empty">
                                <div class="pj-empty-icon"><i class="fas fa-users"></i></div>
                                <p>Belum ada data petugas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        <div class="pj-footer">
            {{ $this->penjagas->links() }}
        </div>

    </div>

    {{-- ══════════════════════════════════════ --}}
    {{-- ── Modal Tambah ── --}}
    {{-- ══════════════════════════════════════ --}}
    @if($addPage)
    <div class="pj-modal-overlay" wire:click.self="$set('addPage', false)">
        <div class="pj-modal">
            <div class="pj-modal-header">
                <p class="pj-modal-title"><i class="fas fa-user-plus" style="margin-right:8px;color:#3B82F6;"></i>Tambah Petugas</p>
                <button class="pj-modal-close" wire:click="$set('addPage', false)">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <div class="pj-modal-body">
                <div class="mfield">
                    <label class="mfield-label">Nama Lengkap</label>
                    <input type="text" class="mfield-input" wire:model="name" placeholder="Masukkan nama lengkap">
                    @error('name') <p class="err-msg">{{ $message }}</p> @enderror
                </div>
                <div class="mfield">
                    <label class="mfield-label">Email</label>
                    <input type="email" class="mfield-input" wire:model="email" placeholder="contoh@email.com">
                    @error('email') <p class="err-msg">{{ $message }}</p> @enderror
                </div>
                <div class="mfield">
                    <label class="mfield-label">Password</label>
                    <input type="password" class="mfield-input" wire:model="password" placeholder="Minimal 6 karakter">
                    @error('password') <p class="err-msg">{{ $message }}</p> @enderror
                </div>
                <div class="mfield">
                    <label class="mfield-label">Alamat</label>
                    <input type="text" class="mfield-input" wire:model="alamat" placeholder="Masukkan alamat">
                    @error('alamat') <p class="err-msg">{{ $message }}</p> @enderror
                </div>
                <div class="mfield">
                    <label class="mfield-label">Role</label>
                    <select class="mfield-select" wire:model="role">
                        <option value="">-- Pilih Role --</option>
                        <option value="petugas">Petugas</option>
                    </select>
                    @error('role') <p class="err-msg">{{ $message }}</p> 
                    @enderror
                </div>
                <div class="modal-actions">
                    <button wire:click="store" class="btn-modal-save">
                        <i class="fas fa-check"></i> Simpan
                    </button>
                    <button wire:click="$set('addPage', false)" class="btn-modal-cancel">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════ --}}
    {{-- ── Modal Edit ── --}}
    {{-- ══════════════════════════════════════ --}}
    @if($editPage)
    <div class="pj-modal-overlay" wire:click.self="$set('editPage', false)">
        <div class="pj-modal">
            <div class="pj-modal-header">
                <p class="pj-modal-title"><i class="fas fa-user-pen" style="margin-right:8px;color:#3B82F6;"></i>Edit Petugas</p>
                <button class="pj-modal-close" wire:click="$set('editPage', false)">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <div class="pj-modal-body">
                <div class="mfield">
                    <label class="mfield-label">Nama Lengkap</label>
                    <input type="text" class="mfield-input" wire:model="name" placeholder="Masukkan nama lengkap">
                    @error('name') <p class="err-msg">{{ $message }}</p> @enderror
                </div>
                <div class="mfield">
                    <label class="mfield-label">Email</label>
                    <input type="email" class="mfield-input" wire:model="email" placeholder="contoh@email.com">
                    @error('email') <p class="err-msg">{{ $message }}</p> @enderror
                </div>
                <div class="mfield">
                    <label class="mfield-label">Password Baru <span style="font-weight:400;text-transform:none;letter-spacing:0;">(opsional)</span></label>
                    <input type="password" class="mfield-input" wire:model="password" placeholder="Kosongkan jika tidak diubah">
                    <p class="mfield-hint">Biarkan kosong jika tidak ingin mengubah password.</p>
                </div>
                <div class="mfield">
                    <label class="mfield-label">Alamat</label>
                    <input type="text" class="mfield-input" wire:model="alamat" placeholder="Masukkan alamat">
                    @error('alamat') <p class="err-msg">{{ $message }}</p> @enderror
                </div>
                <div class="mfield">
                    <label class="mfield-label">Role</label>
                    <select class="mfield-select" wire:model="role">
                        <option value="">-- Pilih Role --</option>
                        <option value="petugas">Petugas</option>
                    </select>
                    @error('role') <p class="err-msg">{{ $message }}</p> @enderror
                </div>
                <div class="modal-actions">
                    <button wire:click="update" class="btn-modal-save">
                        <i class="fas fa-check"></i> Update
                    </button>
                    <button wire:click="$set('editPage', false)" class="btn-modal-cancel">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
