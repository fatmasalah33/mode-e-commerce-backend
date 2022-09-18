<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $table='offeres';
    protected $fillable=[
        'end_at',
        'percent',
        'product_id'

    ];
    public function products()
    {
        return $this->hasOne(Product::class,"id","product_id");
    }

}
