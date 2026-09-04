@extends('layouts.app')
@section('title', 'Thi')
@section('content')
<table class="table table-bordered bg-white">
    <thead><tr><th>Kỳ thi</th><th>Ngày thi</th><th>Phòng</th><th>Trạng thái ca thi</th><th></th></tr></thead>
    <tbody>
    @forelse ($dangKys as $dk)
        <tr>
            <td>{{ $dk->lichThi->ten_ky_thi }}</td>
            <td>{{ $dk->lichThi->ngay_thi->format('d/m/Y') }} {{ \Illuminate\Support\Carbon::parse($dk->lichThi->gio_bat_dau)->format('H:i') }}</td>
            <td>{{ $dk->lichThi->phong_thi }}</td>
            <td><span class="badge text-bg-secondary">{{ $dk->lichThi->trang_thai }}</span></td>
            <td><a href="{{ route('sinhvien.thi.chi-tiet', $dk) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a></td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-muted">Chưa có ca thi nào sẵn sàng.</td></tr>
    @endforelse
    </tbody>
</table>
@endsection
