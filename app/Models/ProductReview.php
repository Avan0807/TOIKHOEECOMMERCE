<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ProductReview extends Model
{
    protected $fillable=['user_id','product_id','rate','review','status'];

    public function user_info()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public static function getAllReview(){
        return ProductReview::with('user_info')->paginate(10);
    }
    public static function getAllUserReview(){
        return ProductReview::where('user_id',auth()->user()->id)->with('user_info')->paginate(10);
    }

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
