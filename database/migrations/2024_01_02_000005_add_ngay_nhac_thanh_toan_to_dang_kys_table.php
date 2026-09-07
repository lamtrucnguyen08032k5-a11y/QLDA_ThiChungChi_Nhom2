<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dang_kys', function (Blueprint $table) {
            $table->timestamp('ngay_nhac_thanh_toan')->nullable()->after('ngay_thanh_toan');
        });
    }

    public function down(): void
    {
        Schema::table('dang_kys', function (Blueprint $table) {
            $table->dropColumn('ngay_nhac_thanh_toan');
        });
    }
};
