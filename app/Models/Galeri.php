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
        'judul',
        'file_gambar',
    ];

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
