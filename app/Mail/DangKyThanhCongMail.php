<?php

namespace App\Mail;

use App\Models\DangKy;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// Gửi khi sinh viên đăng ký + thanh toán lệ phí thi thành công
class DangKyThanhCongMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public DangKy $dangKy)
    {
    }

    public function build()
    {
        return $this->subject('[HVNH] Xác nhận đăng ký dự thi - ' . $this->dangKy->ma_dang_ky)
            ->view('emails.dangky-thanh-cong')
            ->with(['dangKy' => $this->dangKy]);
    }
}
