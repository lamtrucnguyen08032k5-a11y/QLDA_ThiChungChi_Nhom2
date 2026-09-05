@extends('layouts.sinhvien')
@section('title', 'Tổng quan')
@section('content')
<h5 class="mb-3">Đăng ký gần đây của bạn</h5>
<table class="table table-bordered bg-white">
    <thead><tr><th>Kỳ thi</th><th>Ngày thi</th><th>Trạng thái đăng ký</th><th>Trạng thái bài thi</th></tr></thead>
    <tbody>
    @forelse ($dangKys as $dk)
        <tr>
            <td>{{ $dk->lichThi->ten_ky_thi }}</td>
            <td>{{ $dk->lichThi->ngay_thi->format('d/m/Y') }}</td>
            <td>{{ $dk->trang_thai }}</td>
            <td>{{ $dk->baiThi->trang_thai ?? '—' }}</td>
        </tr>
    @empty
        <tr><td colspan="4" class="text-muted">Bạn chưa đăng ký thi nào. <a href="{{ route('sinhvien.dangky.index') }}">Đăng ký ngay</a></td></tr>
    @endforelse
    </tbody>
</table>
@endsection
