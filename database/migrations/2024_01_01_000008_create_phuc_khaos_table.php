<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('phuc_khaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bai_thi_id')->constrained('bai_this')->cascadeOnDelete();
            $table->foreignId('sinh_vien_id')->constrained('users')->cascadeOnDelete();
            $table->text('ly_do');
            // cho_xu_ly | dang_xu_ly | da_xu_ly | tu_choi
            $table->string('trang_thai')->default('cho_xu_ly');
            $table->text('phan_hoi')->nullable();
            $table->decimal('diem_truoc', 5, 2)->nullable();
            $table->decimal('diem_sau', 5, 2)->nullable();
            $table->foreignId('xu_ly_boi')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ngay_xu_ly')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phuc_khaos');
    }
};
