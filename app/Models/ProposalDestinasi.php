<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalDestinasi extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'proposal_destinasi';

    protected $fillable = [
        'uuid',
        'proposal_id',
        'destinasi_id',
        'htm_snapshot',
    ];

    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }

    public function destinasi()
    {
        return $this->belongsTo(Destinasi::class, 'destinasi_id');
    }
}
