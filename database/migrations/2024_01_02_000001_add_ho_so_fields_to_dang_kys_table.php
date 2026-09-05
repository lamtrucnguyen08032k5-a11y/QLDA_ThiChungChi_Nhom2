<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dang_kys', function (Blueprint $table) {
            // Mã đăng ký sinh tự động, dùng để tra cứu / hiển thị cho sinh viên
            $table->string('ma_dang_ky')->nullable()->unique()->after('id');

            // --- Thông tin cá nhân (sinh viên nhập ở Bước 1) ---
            $table->string('so_dien_thoai')->nullable()->after('ngay_duyet');
            $table->date('ngay_sinh')->nullable()->after('so_dien_thoai');
            $table->string('gioi_tinh')->nullable()->after('ngay_sinh'); // nam | nu | khac
            $table->string('dan_toc')->nullable()->after('gioi_tinh');
            $table->string('noi_sinh')->nullable()->after('dan_toc');

            // --- Thông tin pháp lý ---
            $table->string('so_cccd')->nullable()->after('noi_sinh');
            $table->string('anh_cccd_truoc')->nullable()->after('so_cccd');
            $table->string('anh_cccd_sau')->nullable()->after('anh_cccd_truoc');
            $table->string('anh_ho_so')->nullable()->after('anh_cccd_sau'); // ảnh hồ sơ dự thi 4x6
            $table->string('anh_the_sv')->nullable()->after('anh_ho_so'); // không bắt buộc

            // --- Bổ sung hồ sơ (Admin yêu cầu) ---
            $table->json('truong_can_bo_sung')->nullable()->after('anh_the_sv');
            $table->text('ly_do_bo_sung')->nullable()->after('truong_can_bo_sung');
            $table->timestamp('han_bo_sung')->nullable()->after('ly_do_bo_sung');

            // --- Thanh toán ---
            // vnpay | napas
            $table->string('phuong_thuc_thanh_toan')->nullable()->after('han_bo_sung');
            // cho_thanh_toan | da_thanh_toan | thanh_toan_that_bai
            $table->string('trang_thai_thanh_toan')->default('cho_thanh_toan')->after('phuong_thuc_thanh_toan');
            $table->string('ma_giao_dich')->nullable()->after('trang_thai_thanh_toan');
            $table->unsignedBigInteger('so_tien')->nullable()->after('ma_giao_dich');
            $table->timestamp('ngay_thanh_toan')->nullable()->after('so_tien');
        });
    }

    public function down(): void
    {
        Schema::table('dang_kys', function (Blueprint $table) {
            $table->dropColumn([
                'ma_dang_ky', 'so_dien_thoai', 'ngay_sinh', 'gioi_tinh', 'dan_toc', 'noi_sinh',
                'so_cccd', 'anh_cccd_truoc', 'anh_cccd_sau', 'anh_ho_so', 'anh_the_sv',
                'truong_can_bo_sung', 'ly_do_bo_sung', 'han_bo_sung',
                'phuong_thuc_thanh_toan', 'trang_thai_thanh_toan', 'ma_giao_dich', 'so_tien', 'ngay_thanh_toan',
            ]);
        });
    }
};
