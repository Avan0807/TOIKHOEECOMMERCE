<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateOrder extends Model
{
    use HasFactory;

    // Chỉ định bảng trong cơ sở dữ liệu
    protected $table = 'affiliate_orders';

    // Các trường có thể gán giá trị
    protected $fillable = [
        'order_id',
        'doctor_id',
        'commission',
        'status',
    ];

    // Nếu muốn, có thể chỉ định các trường không thể gán giá trị (tùy theo cấu trúc của bạn)
    // protected $guarded = ['id'];

    // Nếu bảng sử dụng timestamp tự động (created_at, updated_at)
    public $timestamps = true;
}
