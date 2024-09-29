<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotRTS extends Model
{
    use HasFactory;

    protected $table = 'slots_rts';

    protected $fillable = [
        'day',
        'time',
        'is_available',
        'date'
    ];
}
