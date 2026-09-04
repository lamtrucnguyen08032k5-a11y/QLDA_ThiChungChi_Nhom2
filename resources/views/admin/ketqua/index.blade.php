@extends('layouts.app')
@section('title', 'Kết quả thi')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>{{ $lichthi->ten_ky_thi }} - Kết quả</h5>
    <form method="POST" action="{{ route('admin.ketqua.congbo', $lichthi) }}" onsubmit="return confirm('Công bố kết quả cho tất cả bài đã chấm xong?')">
        @csrf
        <button class="btn btn-success btn-sm">Công bố kết quả</button>
    </form>
</div>
<table class="table table-bordered bg-white">
    <thead><tr><th>Mã SV</th><th>Họ tên</th><th>Điểm trắc nghiệm</th><th>Điểm tự luận</th><th>Điểm tổng</th><th>Trạng thái chấm</th><th>Đã công bố?</th></tr></thead>
    <tbody>
    @foreach ($baiThis as $bt)
        <tr>
            <td>{{ $bt->dangKy->sinhVien->ma_so }}</td>
            <td>{{ $bt->dangKy->sinhVien->name }}</td>
            <td>{{ $bt->diem_tu_dong }}</td>
            <td>{{ $bt->diem_cham_tay }}</td>
            <td>{{ $bt->diem_tong ?? '—' }}</td>
            <td>{{ $bt->cham_xong ? 'Đã chấm xong' : 'Chưa chấm xong' }}</td>
            <td>{{ $bt->trang_thai === 'da_cong_bo' ? 'Đã công bố' : 'Chưa' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
