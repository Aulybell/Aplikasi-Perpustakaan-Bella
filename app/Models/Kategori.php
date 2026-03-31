<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Kategori extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategoris';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'nama_kategori'];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
     public function buku()
    {
        return $this->hasMany(Buku::class);
    }
}
