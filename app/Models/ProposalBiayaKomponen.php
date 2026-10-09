<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalBiayaKomponen extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'proposal_biaya_komponen';

    protected $fillable = [
        'uuid',
        'proposal_id',
        'nama_komponen',
        'kategori',
        'nominal_satuan',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }
}
