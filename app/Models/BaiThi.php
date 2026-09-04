<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaiThi extends Model
{
    protected $table = 'bai_this';
    protected $fillable = [
        'dang_ky_id', 'de_thi_id', 'gio_bat_dau', 'gio_nop', 'trang_thai',
        'diem_tu_dong', 'diem_cham_tay', 'diem_tong', 'cham_xong',
        'giang_vien_id', 'ngay_cham',
    ];

    protected function casts(): array
    {
        return [
            'gio_bat_dau' => 'datetime',
            'gio_nop' => 'datetime',
            'ngay_cham' => 'datetime',
            'cham_xong' => 'boolean',
        ];
    }

    public function dangKy()
    {
        return $this->belongsTo(DangKy::class);
    }

    public function deThi()
    {
        return $this->belongsTo(DeThi::class);
    }

    public function giangVien()
    {
        return $this->belongsTo(User::class, 'giang_vien_id');
    }

    public function cauTraLois()
    {
        return $this->hasMany(CauTraLoi::class);
    }

    public function phucKhaos()
    {
        return $this->hasMany(PhucKhao::class);
    }

    public function chungNhan()
    {
        return $this->hasOne(ChungNhan::class);
    }

    // Sinh viên qua Đăng ký
    public function sinhVien()
    {
        return $this->dangKy?->sinhVien();
    }
}
