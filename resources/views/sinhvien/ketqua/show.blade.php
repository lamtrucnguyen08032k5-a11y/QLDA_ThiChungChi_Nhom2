@extends('layouts.sinhvien')
@section('title', 'Chi tiết kết quả')
@section('content')
<div class="card mb-3"><div class="card-body">
    <h5>{{ $baithi->dangKy->lichThi->ten_ky_thi }}</h5>
    <p class="fs-4 fw-bold">Điểm tổng: {{ $baithi->diem_tong }}</p>
    <div class="d-flex gap-2">
        <a href="{{ route('sinhvien.phuc-khao.create', $baithi) }}" class="btn btn-outline-warning btn-sm">Yêu cầu phúc khảo</a>
        @if ($baithi->diem_tong >= 50)
            <a href="{{ route('sinhvien.chung-nhan.create', $baithi) }}" class="btn btn-outline-success btn-sm">Đăng ký nhận chứng nhận</a>
        @endif
    </div>
</div></div>

@foreach ($baithi->cauTraLois as $ctl)
    <div class="card mb-2"><div class="card-body">
        <p class="fw-semibold">{{ $ctl->cauHoi->noi_dung }}</p>
        @if ($ctl->cauHoi->loai_cau === 'tracnghiem')
            <p class="small mb-0">Bạn chọn: {{ $ctl->dap_an_chon ?? '(không trả lời)' }} • Điểm: {{ $ctl->diem_dat }}/{{ $ctl->cauHoi->diem }}</p>
        @else
            <p class="small mb-0">Điểm chấm: {{ $ctl->diem_dat }}/{{ $ctl->cauHoi->diem }}</p>
        @endif
    </div></div>
@endforeach
@endsection
