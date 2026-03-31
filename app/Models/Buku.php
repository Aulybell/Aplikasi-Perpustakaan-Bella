<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buku extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bukus';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'judul', 'pengarang', 'penerbit', 'tahun_terbit', 'kategori_id', 'foto', 'stok', 'sinopsis'];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
     public function pinjams()
    {
        return $this->hasMany(Pinjam::class);
    }

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function getRatingAverageAttribute()
    {
        return $this->ulasans()->avg('rating') ?? 0;
    }

    public function getReviewCountAttribute()
    {
        return $this->ulasans()->count();
    }
}
