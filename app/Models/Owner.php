<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Owner extends Model
{
    use HasFactory;
    protected $fillable = ['name','address','phone','status','contract'];

    public function apartment(): HasMany
    {
        return $this->hasMany(Apartment::class);
    }

}