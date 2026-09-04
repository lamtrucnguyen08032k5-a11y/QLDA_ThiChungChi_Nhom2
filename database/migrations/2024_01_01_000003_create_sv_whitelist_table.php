<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    // "Kho mail HVNH" - danh sách SV hợp lệ (Admin import trước), dùng để kiểm tra khi Sinh viên đăng ký
    public function up(): void
    {
        Schema::create('sv_whitelists', function (Blueprint $table) {
            $table->id();
            $table->string('ma_sv')->unique();
            $table->string('ho_ten');
            $table->string('email')->unique();
            $table->string('lop')->nullable();
            $table->string('khoa_hoc')->nullable();
            $table->boolean('da_dang_ky')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sv_whitelists');
    }
};
