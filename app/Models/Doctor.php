<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Doctor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'doctors'; // Tên bảng

    protected $primaryKey = 'id'; // Khóa chính

    public $timestamps = true; // Cho phép timestamps

    protected $fillable = [
        'name',
        'specialization',
        'services',
        'experience',
        'working_hours',
        'location',
        'workplace',
        'phone',
        'email',
        'photo',
        'status',
        'rating',
        'consultation_fee',
        'bio',
        'password',
        'points',
        'total_commission',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => 'string', // Nếu là ENUM, có thể cần chuyển sang string
        'rating' => 'decimal:2',
        'consultation_fee' => 'decimal:2',
        'total_commission' => 'double',
        'experience' => 'integer',
        'points' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Lấy danh sách đánh giá của bác sĩ.
     */

    /**
     * Lấy danh sách đơn hàng liên quan đến bác sĩ (nếu có hệ thống hoa hồng).
     */

    /**
     * Mã hóa mật khẩu khi lưu vào database.
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }
}
