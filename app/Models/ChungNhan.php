<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChungNhan extends Model
{
    protected $table = 'chung_nhans';
    protected $fillable = [
        'bai_thi_id', 'sinh_vien_id', 'so_chung_nhan', 'trang_thai',
        'dia_chi_nhan', 'so_dien_thoai', 'file_chung_nhan', 'ngay_cap',
    ];

    protected function casts(): array
    {
        return ['ngay_cap' => 'datetime'];
    }

    public function baiThi()
    {
        return $this->belongsTo(BaiThi::class);
    }

    public function sinhVien()
    {
        return $this->belongsTo(User::class, 'sinh_vien_id');
    }
}
