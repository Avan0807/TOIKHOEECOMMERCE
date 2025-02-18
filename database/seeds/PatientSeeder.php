<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    public function run()
    {
        // Đổ tạm dữ liệu mẫu vào bảng patients
        Patient::insert([
            [
                'date_check_in' => '2025-01-10',
                'patient_name' => 'Nguyễn Văn A',
                'doctor_assigned' => 'Bác sĩ Trần Văn B',
                'disease' => 'Cảm cúm',
                'status' => 'Đang điều trị',
                'room_no' => '101',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'date_check_in' => '2025-01-08',
                'patient_name' => 'Trần Thị C',
                'doctor_assigned' => 'Bác sĩ Nguyễn Thị D',
                'disease' => 'Viêm họng',
                'status' => 'Đã xuất viện',
                'room_no' => '102',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'date_check_in' => '2025-01-09',
                'patient_name' => 'Lê Văn E',
                'doctor_assigned' => 'Bác sĩ Phạm Văn F',
                'disease' => 'Đau dạ dày',
                'status' => 'Đang điều trị',
                'room_no' => '103',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

