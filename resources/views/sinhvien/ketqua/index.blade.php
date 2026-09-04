@extends('layouts.app')
@section('title', 'Kết quả thi')
@section('content')
<table class="table table-bordered bg-white">
    <thead><tr><th>Kỳ thi</th><th>Đề thi</th><th>Điểm tổng</th><th></th></tr></thead>
    <tbody>
    @forelse ($baiThis as $bt)
        <tr>
            <td>{{ $bt->dangKy->lichThi->ten_ky_thi }}</td>
            <td>{{ $bt->deThi->ten_de }}</td>
            <td class="fw-bold">{{ $bt->diem_tong }}</td>
            <td><a href="{{ route('sinhvien.ketqua.show', $bt) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a></td>
        </tr>
    @empty
        <tr><td colspan="4" class="text-muted">Chưa có kết quả nào được công bố.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
