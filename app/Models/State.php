<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class State extends Model
{
    use HasFactory;
    protected $guarded = [];



    public $timestamps = false;
    public function status()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
