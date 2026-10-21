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
        'nomor_paket',
        'nama_paket',
    ];

    public function proposal()
    {
        return $this->hasMany(Proposal::class, 'paket_wisata_id');
    }
}
