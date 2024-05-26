<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestVendor extends Model
{
    use HasFactory;
    
    protected $fillable = ['service_id', 'work_contents', 'desired_start', 'desired_end', 'price'];

    protected $casts = [
        'service_id' => 'array',
    ];
}