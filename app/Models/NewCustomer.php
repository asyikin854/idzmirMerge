<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewCustomer extends Model
{
    use HasFactory;

    protected $table = 'new_customers';

    protected $fillable = [     'name',
                                'phone',
                                'address',
                                'posscode',
                                'city',
                                'country',
                                'email',
                                'status',
                                'progress',
                                'remark'];
                                
}
