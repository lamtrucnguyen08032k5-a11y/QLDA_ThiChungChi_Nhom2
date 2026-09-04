@extends('layouts.app')
@section('title', 'Xử lý phúc khảo')
@section('content')
<div class="card mb-3"><div class="card-body">
    <p><strong>Sinh viên:</strong> {{ $phuckhao->baiThi->dangKy->sinhVien->name }}</p>
    <p><strong>Lý do phúc khảo:</strong> {{ $phuckhao->ly_do }}</p>
    <p><strong>Điểm hiện tại:</strong> {{ $phuckhao->baiThi->diem_tong }}</p>
</div></div>

@foreach ($phuckhao->baiThi->cauTraLois as $ctl)
    <div class="card mb-2"><div class="card-body">
        <p class="fw-semibold">{{ $ctl->cauHoi->noi_dung }}</p>
        @if ($ctl->cauHoi->loai_cau === 'tracnghiem')
            <p class="small mb-0">Chọn: {{ $ctl->dap_an_chon }} • Đúng: {{ $ctl->cauHoi->dap_an_dung }} • Điểm: {{ $ctl->diem_dat }}</p>
        @else
            <p class="border rounded p-2 bg-light small">{{ $ctl->bai_lam_tu_luan }}</p>
            <p class="small mb-0">Điểm đã chấm: {{ $ctl->diem_dat }} / {{ $ctl->cauHoi->diem }}</p>
        @endif
    </div></div>
@endforeach

<form method="POST" action="{{ route('giangvien.phuc-khao.xuly', $phuckhao) }}" class="card"><div class="card-body">
    @csrf
    <div class="mb-2">
        <label class="form-label">Điểm sau phúc khảo (nếu điều chỉnh)</label>
        <input type="number" step="0.1" name="diem_sau" class="form-control" value="{{ $phuckhao->baiThi->diem_tong }}">
    </div>
    <div class="mb-2">
        <label class="form-label">Phản hồi cho sinh viên</label>
        <textarea name="phan_hoi" class="form-control" required></textarea>
    </div>
    <div class="mb-2">
        <label class="form-label">Quyết định</label>
        <select name="quyet_dinh" class="form-select">
            <option value="da_xu_ly">Chấp nhận / Điều chỉnh điểm</option>
            <option value="tu_choi">Giữ nguyên / Từ chối</option>
        </select>
    </div>
    <button class="btn btn-primary">Gửi phản hồi</button>
</div></form>
@endsection
