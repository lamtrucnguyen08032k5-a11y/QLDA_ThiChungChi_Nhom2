@extends('layouts.app')
@section('title', 'Tiến độ chấm')
@section('content')
<table class="table table-bordered bg-white">
    <thead><tr><th>Sinh viên</th><th>Kỳ thi</th><th>Phòng thi</th><th>Ngày nộp</th><th>Giảng viên chấm</th><th>Trạng thái</th></tr></thead>
    <tbody>
    @foreach ($baiThis as $bt)
        <tr>
            <td>{{ $bt->dangKy->sinhVien->name }}</td>
            <td>{{ $bt->dangKy->lichThi->ten_ky_thi }}</td>
            <td>{{ $bt->dangKy->lichThi->phong_thi }}</td>
            <td>{{ $bt->gio_nop?->format('d/m/Y H:i') }}</td>
            <td>{{ $bt->giangVien->name ?? '—' }}</td>
            <td>{{ $bt->cham_xong ? 'Đã chấm xong' : 'Chưa chấm xong' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $baiThis->links() }}
@endsection
