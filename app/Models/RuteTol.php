<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuteTol extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'rute_tol';

    protected $fillable = [
        'uuid',
        'nama_rute',
        'golongan',
        'tarif_total',
    ];

    public function proposalTol()
    {
        return $this->hasMany(ProposalTol::class, 'rute_tol_id');
    }
}
