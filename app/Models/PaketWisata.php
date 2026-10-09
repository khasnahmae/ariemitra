<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketWisata extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'paket_wisata';

    protected $fillable = [
        'uuid',
        'nama_paket',
        'nama_sub_paket',
        'slug',
        'durasi',
        'harga_mulai_from',
        'fasilitas',
        'is_active',
    ];

    public function destinasi()
    {
        return $this->belongsToMany(Destinasi::class, 'paket_destinasi', 'paket_id', 'destinasi_id')
            ->withPivot('uuid')
            ->withTimestamps();
    }

    public function galeri()
    {
        return $this->hasMany(Galeri::class, 'paket_id');
    }
}
