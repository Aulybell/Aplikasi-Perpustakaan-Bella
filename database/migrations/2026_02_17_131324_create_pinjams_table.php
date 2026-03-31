<?php

use App\Models\Buku;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pinjams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Buku::class);
            $table->string('nama_peminjam')->nullable();
            $table->string('no_telp')->nullable();
            $table->date('tanggal_pinjam')->nullable();
            $table->date('perkiraan_kembali')->nullable();
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['menunggu', 'dipinjam','ditolak','dikembalikan'])->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjams');
    }
};
