<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sizes extends Model
{
    use HasFactory;
    // protected $table='products_size';
    protected $fillable=[
        'size',
        'category_id',
        'quantity',

    ];
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

}
