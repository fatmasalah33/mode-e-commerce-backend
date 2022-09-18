<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table="cart";
    protected $fillable=
    [
        'quantity',
        'price',
        'user_id',
        'product_id'
        
    ];
    public function products()
    {
        return $this->hasMany(Product::class,"id","product_id");
    }
}
