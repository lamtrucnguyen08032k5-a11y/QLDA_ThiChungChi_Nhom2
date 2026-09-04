<?php

namespace App\Http\Controllers\SinhVien;

use App\Http\Controllers\Controller;
use App\Models\BaiThi;
use App\Models\PhucKhao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// M8 - UC8.1 Sinh viên gửi yêu cầu phúc khảo (trong hạn cho phép sau khi công bố kết quả)
class PhucKhaoController extends Controller
{
    const HAN_PHUC_KHAO_NGAY = 7;

    public function create(BaiThi $baithi)
    {
        abort_unless($baithi->dangKy->sinh_vien_id === Auth::id(), 403);
        abort_unless($baithi->trang_thai === 'da_cong_bo', 404, 'Kết quả chưa công bố, chưa thể phúc khảo.');

        return view('sinhvien.phuc-khao.create', compact('baithi'));
    }

    public function store(Request $request, BaiThi $baithi)
    {
        abort_unless($baithi->dangKy->sinh_vien_id === Auth::id(), 403);
        abort_unless($baithi->trang_thai === 'da_cong_bo', 404);

        if ($baithi->phucKhaos()->exists()) {
            return back()->withErrors(['phuckhao' => 'Bạn đã gửi yêu cầu phúc khảo cho bài thi này rồi.']);
        }

        $data = $request->validate(['ly_do' => 'required|string|min:10']);

        PhucKhao::create([
            'bai_thi_id' => $baithi->id,
            'sinh_vien_id' => Auth::id(),
            'ly_do' => $data['ly_do'],
            'trang_thai' => 'cho_xu_ly',
        ]);

        return redirect()->route('sinhvien.phuc-khao.index')->with('status', 'Gửi yêu cầu phúc khảo thành công.');
    }

    public function index()
    {
        $phucKhaos = PhucKhao::with('baiThi.dangKy.lichThi')
            ->where('sinh_vien_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('sinhvien.phuc-khao.index', compact('phucKhaos'));
    }
}
