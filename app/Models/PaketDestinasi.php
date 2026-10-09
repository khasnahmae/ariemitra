<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketDestinasi extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'paket_destinasi';

    protected $fillable = [
        'uuid',
        'paket_id',
        'destinasi_id',
    ];
}
