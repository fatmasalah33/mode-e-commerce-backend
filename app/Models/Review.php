<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'feedback',
        'user_id',
        'product_id',
        'rating',
        'order_id'
    ];
    public function Product(){
        return $this->belongsTo(Product::class,"product_id","id");
    }
    public function User(){
        return $this->belongsTo(User::class,"user_id","id");
    }
}
