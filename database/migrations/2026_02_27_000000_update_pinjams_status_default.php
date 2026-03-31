<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // set default and make not nullable, update existing rows
        Schema::table('pinjams', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'dipinjam','ditolak','dikembalikan'])
                  ->default('menunggu')
                  ->nullable(false)
                  ->change();
        });

        // backfill any null or incorrect status values
        DB::table('pinjams')
            ->whereNull('status')
            ->orWhere('status', '')
            ->update(['status' => 'menunggu']);

        // if some rows were erroneously inserted as "dipinjam" without action,
        // you may want to reset them to "menunggu" or inspect them manually.
        // the following is optional and commented out:
        // DB::table('pinjams')->where('status', 'dipinjam')->update(['status'=>'menunggu']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pinjams', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'dipinjam','ditolak','dikembalikan'])
                  ->nullable()
                  ->default(null)
                  ->change();
        });
    }
};