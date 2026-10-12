<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    public function getGambarUrlAttribute()
    {
        if (!$this->file_gambar) {
            return asset('frontend/images/destination-1.jpg');
        }

        if (Str::startsWith($this->file_gambar, ['http://', 'https://'])) {
            return $this->file_gambar;
        }

        return asset('storage/' . $this->file_gambar);
    }
}
