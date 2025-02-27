<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phoneNumber')->unique()->nullable(); // Số điện thoại
            $table->timestamp('phone_verified_at')->nullable(); // Thời gian xác minh số điện thoại
            $table->string('verification_code')->nullable(); // Mã xác minh
            $table->string('password')->nullable();
            $table->string('photo')->nullable();
            $table->enum('role', ['admin', 'user', 'doctor'])->default('user');
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->rememberToken()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
