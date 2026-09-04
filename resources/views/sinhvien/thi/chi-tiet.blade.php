@extends('layouts.app')
@section('title', 'Chi tiết ca thi')
@section('content')
<div class="card"><div class="card-body">
    <h5>{{ $dangky->lichThi->ten_ky_thi }}</h5>
    <p class="text-muted">Ngày thi: {{ $dangky->lichThi->ngay_thi->format('d/m/Y') }} • Phòng {{ $dangky->lichThi->phong_thi }} • Thời gian: {{ $dangky->lichThi->thoi_gian_thi_phut }} phút</p>

    @if ($dangky->baiThi && $dangky->baiThi->gio_nop)
        <div class="alert alert-success">Bạn đã nộp bài lúc {{ $dangky->baiThi->gio_nop->format('d/m/Y H:i') }}. Trạng thái: {{ $dangky->baiThi->trang_thai }}</div>
    @elseif ($dangky->lichThi->trang_thai !== 'dang_thi')
        <div class="alert alert-warning">Ca thi chưa bắt đầu. Vui lòng quay lại đúng giờ thi.</div>
    @else
        <form method="POST" action="{{ route('sinhvien.thi.nhap-ma', $dangky) }}" class="row g-2">
            @csrf
            <div class="col-md-4">
                <input name="ma_ca_thi" class="form-control" placeholder="Nhập mã ca thi" required>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary">Vào phòng thi</button>
            </div>
        </form>
    @endif
</div></div>
@endsection
