<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTol extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'proposal_tol';

    protected $fillable = [
        'uuid',
        'proposal_id',
        'rute_tol_id',
        'tipe_perjalanan',
        'tarif_snapshot',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function ruteTol()
    {
        return $this->belongsTo(RuteTol::class, 'rute_tol_id');
    }
}
