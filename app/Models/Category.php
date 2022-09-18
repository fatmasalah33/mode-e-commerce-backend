<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id'

    ];
    public function product(){
        return $this->hasMany(Product::class,"category_id","id");
    }
    public function sizes(){
        return $this->hasMany(sizes::class,"category_id","id");
    }
    public function category(){
        return $this->hasMany(Category::class,"category_id","id");
    }
}
