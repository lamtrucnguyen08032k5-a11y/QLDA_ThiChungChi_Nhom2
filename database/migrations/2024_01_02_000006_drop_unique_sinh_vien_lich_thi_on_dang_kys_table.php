<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dang_kys', function (Blueprint $table) {
            // Thêm index cho sinh_vien_id để phục vụ foreign key trước khi drop unique
            $table->index('sinh_vien_id');
            $table->dropUnique(['sinh_vien_id', 'lich_thi_id']);
        });
    }

    public function down(): void
    {
        Schema::table('dang_kys', function (Blueprint $table) {
            $table->unique(['sinh_vien_id', 'lich_thi_id']);
            $table->dropIndex(['sinh_vien_id']);
        });
    }
};
