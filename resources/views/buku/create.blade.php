<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-12">
            <div class="bg-light rounded h-100 p-4">

                <h6 class="mb-4">Add Buku</h6>

                <form wire:submit.prevent="store">

                
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" wire:model="judul"
                            id="judul" value="{{ @old('judul') }}">
                        @error('judul')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="pengarang" class="form-label">Pengarang</label>
                        <input type="text" class="form-control" wire:model="pengarang"
                            id="pengarang" value="{{ @old('pengarang') }}">
                        @error('pengarang')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="penerbit" class="form-label">Penerbit</label>
                        <input type="text" class="form-control" wire:model="penerbit"
                        id="penerbit" value="{{ @old('penerbit') }}">
                        @error('penerbit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- alamat -->
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Tahun Terbit</label>
                        <input type="text" class="form-control" wire:model="tahun_terbit"
                        id="tahun_terbit" value="{{ @old('tahun_terbit') }}">
                        @error('tahun_terbit')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">  
                        <label for="kategori" class="form-label">Kategori</label>
                        @php $_kategoris = \App\Models\Kategori::orderBy('nama_kategori')->get(); @endphp
                        <select id="kategori" class="form-select" wire:model="kategori_id">
                            <option value="">Pilih Kategori</option>
                            @foreach($_kategoris as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" wire:model="foto"
                        id="foto" value="{{ @old('foto') }}">
                        @error('foto')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="text" class="form-control" wire:model="stok"
                        id="stok" value="{{ @old('stok') }}">
                        @error('stok')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="sinopsis" class="form-label">Sinopsis</label>
                        <input type="text" class="form-control" wire:model="sinopsis"
                        id="sinopsis" value="{{ @old('sinopsis') }}">
                        @error('sinopsis')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
</div>
                    

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>