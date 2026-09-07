<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DangKy extends Model
{
    protected $table = 'dang_kys';

    protected $fillable = [
        'sinh_vien_id', 'lich_thi_id', 'trang_thai', 'ly_do_tu_choi', 'ngay_duyet',
        'ma_dang_ky',
        'so_dien_thoai', 'ngay_sinh', 'gioi_tinh', 'dan_toc', 'noi_sinh',
        'tinh_thanh_pho_code', 'tinh_thanh_pho_ten', 'xa_phuong_code', 'xa_phuong_ten',
        'dia_chi_chi_tiet', 'email_lien_he',
        'so_cccd', 'anh_cccd_truoc', 'anh_cccd_sau', 'anh_ho_so', 'anh_the_sv',
        'truong_can_bo_sung', 'ly_do_bo_sung', 'han_bo_sung',
        'phuong_thuc_thanh_toan', 'trang_thai_thanh_toan', 'ma_giao_dich', 'so_tien', 'ngay_thanh_toan',
    ];

    protected function casts(): array
    {
        return [
            'ngay_duyet' => 'datetime',
            'ngay_sinh' => 'date',
            'han_bo_sung' => 'datetime',
            'ngay_thanh_toan' => 'datetime',
            'truong_can_bo_sung' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (DangKy $dangKy) {
            if (empty($dangKy->ma_dang_ky)) {
                $dangKy->ma_dang_ky = 'DK' . now()->format('ymd') . strtoupper(Str::random(5));
            }
        });
    }

    public function sinhVien()
    {
        return $this->belongsTo(User::class, 'sinh_vien_id');
    }

    public function lichThi()
    {
        return $this->belongsTo(LichThi::class);
    }

    public function baiThi()
    {
        return $this->hasOne(BaiThi::class);
    }

    public function diaChiDayDu(): ?string
    {
        $phan = array_filter([$this->dia_chi_chi_tiet, $this->xa_phuong_ten, $this->tinh_thanh_pho_ten]);
        return empty($phan) ? null : implode(', ', $phan);
    }

    public function nhanTrangThaiLabel(): string
    {
        return match ($this->trang_thai) {
            'cho_duyet' => 'Chờ duyệt',
            'cho_bo_sung' => 'Yêu cầu bổ sung',
            'da_duyet' => 'Đã duyệt',
            'tu_choi' => 'Đã từ chối',
            'da_huy' => 'Đã huỷ',
            default => $this->trang_thai,
        };
    }

    public function nhanTrangThaiThanhToanLabel(): string
    {
        return match ($this->trang_thai_thanh_toan) {
            'da_thanh_toan' => 'Đã thanh toán',
            'thanh_toan_that_bai' => 'Thanh toán thất bại',
            default => 'Chờ thanh toán',
        };
    }
}
