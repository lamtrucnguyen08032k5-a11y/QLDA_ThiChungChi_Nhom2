@extends('layouts.app')
@section('title', 'Lịch thi')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Danh sách lịch thi</h5>
    <a href="{{ route('admin.lichthi.create') }}" class="btn btn-primary btn-sm">+ Tạo lịch thi</a>
</div>
<table class="table table-bordered bg-white">
    <thead><tr><th>Tên kỳ thi</th><th>Loại</th><th>Khoa</th><th>Ngày thi</th><th>Phòng</th><th>Mã ca thi</th><th>SL đăng ký</th><th>Trạng thái</th><th></th></tr></thead>
    <tbody>
    @foreach ($lichThis as $lt)
        <tr>
            <td>{{ $lt->ten_ky_thi }}</td>
            <td>{{ $lt->loai_chung_chi === 'cntt' ? 'CNTT' : 'Tiếng Anh' }}</td>
            <td>{{ $lt->khoa->ten_khoa }}</td>
            <td>{{ $lt->ngay_thi->format('d/m/Y') }} {{ \Illuminate\Support\Carbon::parse($lt->gio_bat_dau)->format('H:i') }}</td>
            <td>{{ $lt->phong_thi }}</td>
            <td><code>{{ $lt->ma_ca_thi }}</code></td>
            <td>{{ $lt->dang_kys_count }} / {{ $lt->so_luong_toi_da }}</td>
            <td><span class="badge text-bg-secondary">{{ $lt->trang_thai }}</span></td>
            <td class="text-nowrap">
                <a href="{{ route('admin.dangky.index', $lt) }}" class="btn btn-sm btn-outline-primary">Đăng ký</a>
                <a href="{{ route('admin.ketqua.index', $lt) }}" class="btn btn-sm btn-outline-success">Kết quả</a>
                <a href="{{ route('admin.lichthi.edit', $lt) }}" class="btn btn-sm btn-outline-secondary">Sửa</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $lichThis->links() }}
@endsection
