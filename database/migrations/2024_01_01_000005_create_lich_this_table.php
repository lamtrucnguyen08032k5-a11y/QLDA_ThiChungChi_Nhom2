<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lich_this', function (Blueprint $table) {
            $table->id();
            $table->string('ten_ky_thi');
            $table->string('loai_chung_chi'); // cntt | tienganh
            $table->foreignId('khoa_id')->constrained('khoas')->cascadeOnDelete();
            $table->date('ngay_thi');
            $table->time('gio_bat_dau');
            $table->integer('thoi_gian_thi_phut')->default(60);
            $table->string('phong_thi');
            $table->integer('so_luong_toi_da')->default(50);
            $table->dateTime('han_dang_ky');
            $table->decimal('le_phi', 12, 0)->default(0);
            $table->string('ma_ca_thi')->unique();
            $table->string('trang_thai')->default('dang_mo_dang_ky');
            $table->foreignId('de_thi_id')->nullable()->constrained('de_this')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_this');
    }
};
