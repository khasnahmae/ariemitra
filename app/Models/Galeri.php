<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'galeri';

    protected $fillable = [
        'uuid',
        'paket_id',
        'judul',
        'file_gambar',
    ];

    public function paketWisata()
    {
        return $this->belongsTo(PaketWisata::class, 'paket_id');
    }
}
