<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destinasi extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'destinasi';

    protected $fillable = [
        'uuid',
        'nama_destinasi',
        'lokasi',
        'htm_per_orang',
        'keterangan',
        'foto',
    ];

    public function proposalDestinasi()
    {
        return $this->hasMany(ProposalDestinasi::class, 'destinasi_id');
    }

    public function paketWisata()
    {
        return $this->belongsToMany(PaketWisata::class, 'paket_destinasi', 'destinasi_id', 'paket_id');
    }
}
