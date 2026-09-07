<?php

namespace App\Console\Commands;

use App\Mail\NhacThanhToanMail;
use App\Models\DangKy;
use App\Models\LichSuXuLyHoSo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class KiemTraThanhToanDangKyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dangky:kiem-tra-thanh-toan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quét các hồ sơ đăng ký thi chưa thanh toán: tự động huỷ nếu quá hạn 2 ngày và gửi email nhắc nhở trước 12h';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu kiểm tra trạng thái thanh toán hồ sơ đăng ký thi...');

        $danhSachs = DangKy::with(['sinhVien', 'lichThi'])
            ->where('trang_thai_thanh_toan', 'cho_thanh_toan')
            ->where('trang_thai', '!=', 'da_huy')
            ->get();

        $soLuongHuy = 0;
        $soLuongNhac = 0;

        foreach ($danhSachs as $dk) {
            // 1. Kiểm tra nếu đã quá hạn thanh toán (> 2 ngày hoặc sau hạn ca thi)
            if ($dk->isQuaHanThanhToan()) {
                $dk->update([
                    'trang_thai' => 'da_huy',
                    'trang_thai_thanh_toan' => 'thanh_toan_that_bai',
                ]);

                LichSuXuLyHoSo::create([
                    'dang_ky_id' => $dk->id,
                    'user_id' => null,
                    'vai_tro' => 'admin',
                    'hanh_dong' => 'huy',
                    'trang_thai_truoc' => 'cho_duyet',
                    'trang_thai_sau' => 'da_huy',
                    'noi_dung' => 'Hệ thống tự động huỷ đăng ký do quá hạn thanh toán lệ phí thi (hạn chót: ' . $dk->hanThanhToan()->format('d/m/Y H:i') . ').',
                ]);

                $soLuongHuy++;
                $this->warn("Đã huỷ hồ sơ {$dk->ma_dang_ky} ({$dk->sinhVien->name}) do quá hạn thanh toán.");
                continue;
            }

            // 2. Kiểm tra nếu trong vòng 12 giờ trước khi hết hạn và chưa gửi email nhắc
            if ($dk->isSapHetHanThanhToan() && ! $dk->ngay_nhac_thanh_toan) {
                try {
                    Mail::to($dk->sinhVien->email)
                        ->cc($dk->email_lien_he && $dk->email_lien_he !== $dk->sinhVien->email ? [$dk->email_lien_he] : [])
                        ->send(new NhacThanhToanMail($dk));

                    $dk->update(['ngay_nhac_thanh_toan' => now()]);
                    $soLuongNhac++;
                    $this->info("Đã gửi email nhắc thanh toán cho thí sinh {$dk->sinhVien->name} ({$dk->ma_dang_ky}).");
                } catch (\Throwable $e) {
                    $this->error("Lỗi khi gửi email nhắc nhở hồ sơ {$dk->ma_dang_ky}: " . $e->getMessage());
                }
            }
        }

        $this->info("Hoàn tất! Tự động huỷ: {$soLuongHuy} hồ sơ quá hạn | Gửi nhắc nhở: {$soLuongNhac} thí sinh sắp hết hạn.");
    }
}
