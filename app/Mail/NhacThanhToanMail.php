<?php

namespace App\Mail;

use App\Models\DangKy;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// Gửi khi hồ sơ sắp hết hạn thanh toán lệ phí thi (trong vòng 12h trước hạn)
class NhacThanhToanMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public DangKy $dangKy)
    {
    }

    public function build()
    {
        $han = $this->dangKy->hanThanhToan()->format('H:i d/m/Y');

        return $this->subject('[HVNH] Nhắc nhở hoàn tất lệ phí thi trước ' . $han . ' - Mã: ' . $this->dangKy->ma_dang_ky)
            ->view('emails.nhac-thanh-toan')
            ->with(['dangKy' => $this->dangKy]);
    }
}
