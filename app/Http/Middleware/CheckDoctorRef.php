<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AffiliateLink;

class CheckDoctorRef
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('ref')) {
            $hashRef = $request->query('ref'); // Lấy ref dạng hash từ URL

            // Kiểm tra ref có hợp lệ không
            $affiliate = AffiliateLink::where('hash_ref', $hashRef)->first();

            if (!$affiliate) {
                abort(403, 'Trang chưa có.');
            }

            // Lưu doctor_id vào session để sử dụng sau
            session(['doctor_id' => $affiliate->doctor_id]);
        }

        return $next($request);
    }
}
