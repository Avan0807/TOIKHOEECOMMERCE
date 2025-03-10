<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class DoctorReview extends Model
{
    protected $fillable = ['user_id', 'doctorID', 'rate', 'review', 'status'];


    protected $casts = [
        'rate' => 'float',
        'status' => 'boolean',
    ];

    protected $hidden = ['status'];

    // Liên kết với người dùng
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctorID', 'doctorID'); // Hoặc 'id' nếu khóa chính là id
    }

    // Quan hệ với User
    public function user_info()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }



    // Lấy tất cả các đánh giá
    public static function getPaginatedReviews($perPage = 10)
    {
        return self::with(['user_info', 'doctor'])->paginate($perPage);
    }

    // Lấy đánh giá của người dùng hiện tại
    public static function getUserReviews($perPage = 10)
    {
        if (auth()->check()) {
            return self::where('user_id', auth()->id())->with(['user_info', 'doctor'])->paginate($perPage);
        }

        return collect(); // Trả về collection rỗng nếu không đăng nhập
    }
    public static function getAllReview()
    {
        return self::with(['user_info', 'doctor'])->paginate(10);
    }

    public function getStatusAttribute($value)
    {
        return $value; // Không thay đổi giá trị trả về nếu đã là chuỗi.
    }



}
