<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chung_nhans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bai_thi_id')->constrained('bai_this')->cascadeOnDelete();
            $table->foreignId('sinh_vien_id')->constrained('users')->cascadeOnDelete();
            $table->string('so_chung_nhan')->nullable()->unique();
            // cho_duyet | da_cap | tu_choi
            $table->string('trang_thai')->default('cho_duyet');
            $table->string('dia_chi_nhan')->nullable();
            $table->string('so_dien_thoai')->nullable();
            $table->string('file_chung_nhan')->nullable();
            $table->timestamp('ngay_cap')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chung_nhans');
    }
};
