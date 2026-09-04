@extends('layouts.app')
@section('title', 'Chấm bài thi')
@section('content')
<div class="card mb-3"><div class="card-body">
    <p class="mb-1"><strong>Sinh viên:</strong> {{ $baithi->dangKy->sinhVien->name }} ({{ $baithi->dangKy->sinhVien->ma_so }})</p>
    <p class="mb-0"><strong>Kỳ thi:</strong> {{ $baithi->dangKy->lichThi->ten_ky_thi }} • Điểm trắc nghiệm (tự động): {{ $baithi->diem_tu_dong }}</p>
</div></div>

<form method="POST" action="{{ route('giangvien.cham-thi.luu', $baithi) }}">
    @csrf
    @foreach ($baithi->cauTraLois as $ctl)
        <div class="card mb-2"><div class="card-body">
            <p class="fw-semibold">Câu {{ $loop->iteration }} ({{ $ctl->cauHoi->loai_cau === 'tracnghiem' ? 'Trắc nghiệm' : 'Tự luận' }} - {{ $ctl->cauHoi->diem }} điểm)</p>
            <p>{{ $ctl->cauHoi->noi_dung }}</p>
            @if ($ctl->cauHoi->loai_cau === 'tracnghiem')
                <p class="text-muted small mb-1">Sinh viên chọn: <strong>{{ $ctl->dap_an_chon ?? '(không trả lời)' }}</strong> • Đáp án đúng: <strong>{{ $ctl->cauHoi->dap_an_dung }}</strong></p>
                <p class="mb-0">Điểm đạt (tự động): <strong>{{ $ctl->diem_dat }}</strong></p>
            @else
                <p class="border rounded p-2 bg-light">{{ $ctl->bai_lam_tu_luan ?: '(sinh viên không trả lời)' }}</p>
                <label class="form-label small">Điểm chấm (tối đa {{ $ctl->cauHoi->diem }})</label>
                <input type="number" step="0.1" min="0" max="{{ $ctl->cauHoi->diem }}" name="diem[{{ $ctl->id }}]" class="form-control form-control-sm w-auto" value="{{ $ctl->diem_dat }}">
            @endif
        </div></div>
    @endforeach
    <button class="btn btn-primary">Lưu điểm & Hoàn tất chấm</button>
</form>
@endsection
