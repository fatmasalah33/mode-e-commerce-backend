<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table="orders";

    protected $fillable = [
        'status',
        'copoun',
        'user_id',
        'comment',
        'price',
        'payment_id',
    ];
    public function orderdetails()
    {
        return $this->hasMany(orderdetails::class,'order_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    public function buyerAddress()
    {
        return $this->belongsToMany(buyerAddresses::class);
    }
}
