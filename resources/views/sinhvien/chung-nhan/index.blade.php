@extends('layouts.sinhvien')
@section('title', 'Chứng nhận của tôi')
@section('content')
<table class="table table-bordered bg-white">
    <thead><tr><th>Đề thi</th><th>Số chứng nhận</th><th>Trạng thái</th><th>Ngày cấp</th></tr></thead>
    <tbody>
    @forelse ($chungNhans as $cn)
        <tr>
            <td>{{ $cn->baiThi->deThi->ten_de }}</td>
            <td>{{ $cn->so_chung_nhan ?? '—' }}</td>
            <td><span class="badge text-bg-secondary">{{ $cn->trang_thai }}</span></td>
            <td>{{ $cn->ngay_cap?->format('d/m/Y') ?? '—' }}</td>
        </tr>
    @empty
        <tr><td colspan="4" class="text-muted">Bạn chưa đăng ký nhận chứng nhận nào.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
