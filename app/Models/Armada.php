<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Armada extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'armada';

    protected $fillable = [
        'uuid',
        'jenis_armada',
        'kapasitas',
        'sewa_inc_solar',
    ];

    public function proposalArmada()
    {
        return $this->hasMany(ProposalArmada::class, 'armada_id');
    }
}
