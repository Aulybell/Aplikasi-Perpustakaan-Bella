<?php

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
        Schema::table('pinjams', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'dipinjam', 'ditolak', 'dikembalikan', 'pengembalian_menunggu', 'pengembalian_ditolak'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pinjams', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'dipinjam', 'ditolak', 'dikembalikan'])->nullable()->change();
        });
    }
};
