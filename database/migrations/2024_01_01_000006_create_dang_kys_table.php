<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dang_kys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sinh_vien_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lich_thi_id')->constrained('lich_this')->cascadeOnDelete();
            // cho_duyet | da_duyet | tu_choi | da_huy
            $table->string('trang_thai')->default('cho_duyet');
            $table->string('ly_do_tu_choi')->nullable();
            $table->timestamp('ngay_duyet')->nullable();
            $table->timestamps();
            $table->unique(['sinh_vien_id', 'lich_thi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dang_kys');
    }
};
