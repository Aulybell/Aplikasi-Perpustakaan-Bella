<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">

                <h6 class="mb-4">Edit Penjaga</h6>

                <form wire:submit.prevent="update">

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" wire:model="name"
                            id="name" value="{{ @old('name') }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" wire:model="email"
                            id="email" value="{{ @old('email') }}">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" class="form-control" wire:model="password"
                        id="password" value="{{ @old('password') }}">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Alamat -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" wire:model="alamat"
                        id="alamat" value="{{ @old('alamat') }}">
                        @error('alamat')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Role -->
                    <div class="mb-3">  
                        <label for="role" class="form-label">Role</label>
                        <select id="role" class="form-select" wire:model="role">
                            <option value="">Pilih Role</option>
                            <option value="petugas">Petugas</option>
                            </select>
                        @error('role')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>

                    <!-- Button -->
                    <button type="button" wire:click="update" class="btn btn-primary">
                        Update
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>
