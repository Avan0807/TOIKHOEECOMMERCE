<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id(); // Patient ID
            $table->date('date_check_in'); // Ngày nhập viện
            $table->string('patient_name'); // Tên bệnh nhân
            $table->string('doctor_assigned'); // Bác sĩ phụ trách
            $table->string('disease'); // Bệnh
            $table->string('status'); // Trạng thái (VD: Đang điều trị, Đã xuất viện)
            $table->string('room_no'); // Số phòng
            $table->timestamps(); // created_at và updated_at
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
