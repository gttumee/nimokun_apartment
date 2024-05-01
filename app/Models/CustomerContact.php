<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerContact extends Model
{
    use HasFactory;
    protected $fillable = ['name','info','status','apartment_id'];
    
    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

}