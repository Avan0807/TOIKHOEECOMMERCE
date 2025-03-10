<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $doctors = [
            [
                'name' => 'Test1',
                'specialization' => 'Cardiologist',
                'email' => 'test1@example.com',
                'phone' => '0123456789',
                'location' => 'Hà Nội',
                'working_hours' => '8:00 AM - 5:00 PM',
                'status' => 1,
                'experience' => 5, // Thêm giá trị kinh nghiệm
            ],
            [
                'name' => 'Test2',
                'specialization' => 'Dermatologist',
                'email' => 'test2@example.com',
                'phone' => '0987654321',
                'location' => 'Hồ Chí Minh',
                'working_hours' => '9:00 AM - 6:00 PM',
                'status' => 1,
                'experience' => 10, // Thêm giá trị kinh nghiệm
            ],
        ];
        

        foreach ($doctors as $doctor) {
            Doctor::create($doctor);
        }
    }
}
