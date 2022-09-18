<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class buyerAddresses extends Model
{
    use HasFactory;
    protected $table="buyeraddresses";
    protected $fillable=
    [
       'user_id',
       'address_state',
       'address_city',
       'address_street',
       'name',
       'phone',
       'default'
    ];


}
