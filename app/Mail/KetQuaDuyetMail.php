<?php

namespace App\Mail;

use App\Models\DangKy;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// Gửi khi Admin duyệt / từ chối / yêu cầu bổ sung hồ sơ đăng ký dự thi
class KetQuaDuyetMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public DangKy $dangKy)
    {
    }

    public function build()
    {
        $tieuDe = match ($this->dangKy->trang_thai) {
            'da_duyet' => '[HVNH] Hồ sơ đăng ký dự thi đã được DUYỆT',
            'tu_choi' => '[HVNH] Hồ sơ đăng ký dự thi bị TỪ CHỐI',
            'cho_bo_sung' => '[HVNH] Yêu cầu BỔ SUNG hồ sơ đăng ký dự thi',
            default => '[HVNH] Cập nhật trạng thái hồ sơ đăng ký dự thi',
        };

        return $this->subject($tieuDe . ' - ' . $this->dangKy->ma_dang_ky)
            ->view('emails.ket-qua-duyet')
            ->with(['dangKy' => $this->dangKy]);
    }
}
