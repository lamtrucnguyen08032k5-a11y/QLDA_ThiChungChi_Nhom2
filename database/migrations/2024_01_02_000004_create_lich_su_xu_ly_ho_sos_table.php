<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lich_su_xu_ly_ho_sos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dang_ky_id')->constrained('dang_kys')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('vai_tro')->default('admin'); // admin | sinh_vien | he_thong
            $table->string('hanh_dong'); // tao_ho_so | duyet | yeu_cau_bo_sung | bo_sung_ho_so | tu_choi | huy
            $table->string('trang_thai_truoc')->nullable();
            $table->string('trang_thai_sau');
            $table->text('noi_dung')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_su_xu_ly_ho_sos');
    }
};
