<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'parent_id',
        'payment_id',
        'reference',
        'total_amount',
        'currency',
        'payment_method',
        'status',
        'description',
        'payment_date',
        'session_id',
        'filename',
        'path'
    ];

    public function childInfo()
    {
        return $this->belongsTo(ChildInfo::class, 'child_id');
    }

    public function parentAccount()
    {
        return $this->belongsTo(ParentAccount::class, 'parent_id');
    }
}
