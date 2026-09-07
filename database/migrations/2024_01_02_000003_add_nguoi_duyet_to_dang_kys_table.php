<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dang_kys', function (Blueprint $table) {
            $table->foreignId('nguoi_duyet_id')->nullable()->after('ngay_duyet')->constrained('users')->nullOnDelete();
            $table->timestamp('ngay_bo_sung')->nullable()->after('han_bo_sung');
        });
    }

    public function down(): void
    {
        Schema::table('dang_kys', function (Blueprint $table) {
            $table->dropForeign(['nguoi_duyet_id']);
            $table->dropColumn(['nguoi_duyet_id', 'ngay_bo_sung']);
        });
    }
};
