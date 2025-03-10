<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id('doctorID'); // ID bác sĩ
            $table->string('name', 30); // Tên bác sĩ (giới hạn 30 ký tự)
            $table->string('specialization', 100); // Chuyên môn (giới hạn 100 ký tự)
            $table->integer('experience'); // Kinh nghiệm (năm)
            $table->string('working_hours', 255); // Giờ làm việc
            $table->text('location')->nullable(); // Địa điểm
            $table->string('phone', 255); // Số điện thoại
            $table->string('email', 255)->unique(); // Email (phải duy nhất)
            $table->string('photo', 255)->nullable(); // Ảnh đại diện
            $table->enum('status', ['active', 'inactive'])->default('active'); // Trạng thái
            $table->tinyInteger('rating')->nullable(); // Đánh giá
            $table->text('bio')->nullable(); // Tiểu sử
            $table->string('password', 255); // Mật khẩu
            $table->text('services')->nullable(); // Dịch vụ
            $table->string('workplace')->nullable(); // Nơi công tác
            $table->text('education')->nullable(); // Quá trình đào tạo
            $table->decimal('consultation_fee', 10, 2)->default(0); // Giá tư vấn

            $table->timestamps(); // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}
