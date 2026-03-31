<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pinjam extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pinjams';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'buku_id', 'nama_peminjam', 'no_telp', 'tanggal_pinjam','perkiraan_kembali', 'tanggal_kembali', 'status'];

    // ketika membuat model baru secara manual, default status menunggu
    protected $attributes = [
        'status' => 'menunggu',
    ];

    // cast date fields ke Carbon object
    protected $casts = [
        'tanggal_pinjam' => 'datetime:Y-m-d',
        'perkiraan_kembali' => 'datetime:Y-m-d',
        'tanggal_kembali' => 'datetime:Y-m-d',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function buku():BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }

    /**
     * Hitung denda berdasarkan selisih hari keterlambatan.
     * Asumsikan tarif denda bisa dikonfigurasi, misalnya 5000 per hari.
     */
    public function getDendaAttribute()
    {
        // hanya dikenakan denda bila sudah dikembalikan dan ada tanggal perkiraan
        if (! $this->tanggal_kembali || ! $this->perkiraan_kembali) {
            return 0;
        }

        $actual = \Carbon\Carbon::parse($this->tanggal_kembali)->startOfDay();
        $due = \Carbon\Carbon::parse($this->perkiraan_kembali)->startOfDay();

        if ($actual->lte($due)) {
            return 0;
        }

        $daysLate = $due->diffInDays($actual);
        $rate = 2000; // tarif tetap 2.000 per hari
        return $daysLate * $rate;
    }
}
