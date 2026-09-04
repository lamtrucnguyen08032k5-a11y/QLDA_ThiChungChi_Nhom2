<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DangKy extends Model
{
    protected $table = 'dang_kys';
    protected $fillable = [
        'sinh_vien_id', 'lich_thi_id', 'trang_thai', 'ly_do_tu_choi', 'ngay_duyet',
    ];

    protected function casts(): array
    {
        return ['ngay_duyet' => 'datetime'];
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
}
