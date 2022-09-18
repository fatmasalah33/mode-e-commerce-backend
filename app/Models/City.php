<?php

namespace App\Models;
use App\Models\Country;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class City extends Model
{
    use HasFactory;
    protected $guarded=[];
    public $timestamps = false;
    public function status()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
}
