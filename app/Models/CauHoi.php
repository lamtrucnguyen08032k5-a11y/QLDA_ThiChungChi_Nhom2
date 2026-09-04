<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauHoi extends Model
{
    protected $table = 'cau_hois';
    protected $fillable = [
        'de_thi_id', 'noi_dung', 'loai_cau', 'dap_an_a', 'dap_an_b',
        'dap_an_c', 'dap_an_d', 'dap_an_dung', 'diem', 'thu_tu',
    ];

    public function deThi()
    {
        return $this->belongsTo(DeThi::class);
    }
}
