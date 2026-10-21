<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'proposal';

    protected $fillable = [
        'uuid',
        'paket_wisata_id',
        'nama_sub_paket',
        'user_id',
        'jumlah_hari',
        'jumlah_malam',
        'is_menginap',
        'jumlah_peserta',
        'fasilitas',
        'total_biaya_fixed',
        'biaya_fixed_per_pax',
        'biaya_var_per_pax',
        'margin_per_pax',
        'harga_akhir_per_pax',
        'status',
    ];

    public function PaketWisata()
    {
        return $this->belongsTo(PaketWisata::class, 'paket_wisata_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function proposalDestinasi()
    {
        return $this->hasMany(ProposalDestinasi::class, 'proposal_id');
    }

    public function proposalTol()
    {
        return $this->hasMany(ProposalTol::class, 'proposal_id');
    }

    public function proposalArmada()
    {
        return $this->hasMany(ProposalArmada::class, 'proposal_id');
    }

    public function proposalBiayaKomponen()
    {
        return $this->hasMany(ProposalBiayaKomponen::class, 'proposal_id');
    }
}
