<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Apartment extends Model
{
    use HasFactory;
    protected $fillable = ['name','room_count','image','owner_id','qr_text'];

    public function owner(): belongsTo
    {
        return $this->belongsTo(Owner::class);
    }

    public function customer(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function customerContact(): HasMany
    {
        return $this->hasMany(customerContact::class);
    }
}