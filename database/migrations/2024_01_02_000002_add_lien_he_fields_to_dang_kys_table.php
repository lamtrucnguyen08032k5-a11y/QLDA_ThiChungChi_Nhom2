<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dang_kys', function (Blueprint $table) {
            // Thông tin liên hệ (khác với email tài khoản @hvnh.edu.vn)
            $table->string('tinh_thanh_pho_code')->nullable()->after('noi_sinh');
            $table->string('tinh_thanh_pho_ten')->nullable()->after('tinh_thanh_pho_code');
            $table->string('xa_phuong_code')->nullable()->after('tinh_thanh_pho_ten');
            $table->string('xa_phuong_ten')->nullable()->after('xa_phuong_code');
            $table->string('dia_chi_chi_tiet')->nullable()->after('xa_phuong_ten'); // Số nhà, đường/phố
            $table->string('email_lien_he')->nullable()->after('dia_chi_chi_tiet');
        });
    }

    public function down(): void
    {
        Schema::table('dang_kys', function (Blueprint $table) {
            $table->dropColumn([
                'tinh_thanh_pho_code', 'tinh_thanh_pho_ten',
                'xa_phuong_code', 'xa_phuong_ten',
                'dia_chi_chi_tiet', 'email_lien_he',
            ]);
        });
    }
};
