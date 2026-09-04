<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bai_this', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dang_ky_id')->constrained('dang_kys')->cascadeOnDelete();
            $table->foreignId('de_thi_id')->constrained('de_this')->cascadeOnDelete();
            $table->dateTime('gio_bat_dau')->nullable();
            $table->dateTime('gio_nop')->nullable();
            // dang_thi | da_nop | dang_cham | da_cham | da_cong_bo
            $table->string('trang_thai')->default('dang_thi');
            $table->decimal('diem_tu_dong', 5, 2)->default(0); // điểm phần trắc nghiệm tự chấm
            $table->decimal('diem_cham_tay', 5, 2)->default(0); // điểm phần tự luận GV chấm
            $table->decimal('diem_tong', 5, 2)->nullable();
            $table->boolean('cham_xong')->default(false);
            $table->foreignId('giang_vien_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ngay_cham')->nullable();
            $table->timestamps();
        });

        Schema::create('cau_tra_lois', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bai_thi_id')->constrained('bai_this')->cascadeOnDelete();
            $table->foreignId('cau_hoi_id')->constrained('cau_hois')->cascadeOnDelete();
            $table->string('dap_an_chon')->nullable(); // A/B/C/D
            $table->text('bai_lam_tu_luan')->nullable();
            $table->decimal('diem_dat', 5, 2)->default(0);
            $table->boolean('da_cham')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cau_tra_lois');
        Schema::dropIfExists('bai_this');
    }
};
