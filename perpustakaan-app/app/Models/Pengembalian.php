<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    protected $fillable = ['peminjaman_id', 'tanggal_dikembalikan', 'denda'];

    protected function casts(): array
    {
        return ['tanggal_dikembalikan' => 'date'];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }
}