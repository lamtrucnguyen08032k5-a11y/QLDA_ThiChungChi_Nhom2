<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhucKhao extends Model
{
    protected $table = 'phuc_khaos';
    protected $fillable = [
        'bai_thi_id', 'sinh_vien_id', 'ly_do', 'trang_thai', 'phan_hoi',
        'diem_truoc', 'diem_sau', 'xu_ly_boi', 'ngay_xu_ly',
    ];

    protected function casts(): array
    {
        return ['ngay_xu_ly' => 'datetime'];
    }

    public function baiThi()
    {
        return $this->belongsTo(BaiThi::class);
    }

    public function sinhVien()
    {
        return $this->belongsTo(User::class, 'sinh_vien_id');
    }

    public function xuLyBoi()
    {
        return $this->belongsTo(User::class, 'xu_ly_boi');
    }
}
