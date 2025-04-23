<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'package_id',
        'session',
        'day',
        'time',
        'price',
        'therapist',
        'attendance',
        'remark',
        'status',
        'date',
        'session_id',
        'type'
    ];

    public function childInfo()
    {
        return $this->belongsTo(ChildInfo::class, 'child_id', 'id');
    }
    public function sessionReport()
    {
        return $this->hasOne(SessionReport::class, 'schedules_id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
    
}
