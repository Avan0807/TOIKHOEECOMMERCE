<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class AdDocController extends Controller
{
    // Hiển thị danh sách bác sĩ
    public function index(Request $request)
    {
        $query = Doctor::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('specialization', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        $doctors = $query->paginate(10);

        return view('backend.doctors.index', compact('doctors'));
    }


    public function create()
    {
        return view('backend.doctors.create');
    }


    // Lưu bác sĩ mới vào cơ sở dữ liệu
    public function store(Request $request)
    {
        $this->validateDoctor($request);

        \DB::transaction(function () use ($request) {
            Doctor::create([
                'name' => $request->name,
                'specialization' => $request->specialization,
                'experience' => $request->experience,
                'working_hours' => json_encode($request->working_hours),
                'location' => $request->location,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'status' => $request->status ?? false,
                'rating' => $request->rating ?? 0.0,
                'consultation_fee' => $request->consultation_fee ?? 0.00,
                'bio' => $request->bio ?? '',
                'services' => $request->services ?? '',
                'workplace' => $request->workplace ?? '',
                'education' => $request->education ?? '',
            ]);
        });

        return redirect()->route('doctors.index')->with('success', 'Bác sĩ đã được thêm thành công!');
    }

    // Hiển thị form chỉnh sửa bác sĩ
    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('backend.doctors.edit', compact('doctor'));
    }

    // Cập nhật thông tin bác sĩ
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $this->validateDoctor($request, $id);

        $doctor->update([
            'name' => $request->name,
            'specialization' => $request->specialization,
            'experience' => $request->experience,
            'working_hours' => json_encode($request->working_hours),
            'location' => $request->location,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $doctor->password,
            'status' => $request->status ?? $doctor->status,
            'rating' => $request->rating ?? $doctor->rating,
            'consultation_fee' => $request->consultation_fee ?? $doctor->consultation_fee,
            'bio' => $request->bio ?? $doctor->bio,
            'services' => $request->services ?? $doctor->services,
            'workplace' => $request->workplace ?? $doctor->workplace,
            'education' => $request->education ?? $doctor->education,
        ]);

        return redirect()->route('backend.doctors.index')->with('success', 'Thông tin bác sĩ đã được cập nhật!');
    }

    // Xóa bác sĩ
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);

        if ($doctor->delete()) {
            return redirect()->route('doctors.index')->with('success', 'Bác sĩ đã được xóa thành công!');
        }

        return redirect()->route('doctors.index')->with('error', 'Không thể xóa bác sĩ. Vui lòng thử lại!');
    }

}
