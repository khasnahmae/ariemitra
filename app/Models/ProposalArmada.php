<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalArmada extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'proposal_armada';

    protected $fillable = [
        'uuid',
        'proposal_id',
        'armada_id',
        'jumlah_unit',
        'sewa_snapshot',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function armada()
    {
        return $this->belongsTo(Armada::class, 'armada_id');
    }
}
