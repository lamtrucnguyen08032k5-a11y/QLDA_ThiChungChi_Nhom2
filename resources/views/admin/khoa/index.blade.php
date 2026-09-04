@extends('layouts.app')
@section('title', 'Quản lý Khoa')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Danh sách Khoa</h5>
    <a href="{{ route('admin.khoa.create') }}" class="btn btn-primary btn-sm">+ Thêm mới Khoa</a>
</div>
<table class="table table-bordered bg-white">
    <thead><tr><th>Mã khoa</th><th>Tên khoa</th><th>Email</th><th>Số Giảng viên</th><th>Trạng thái</th><th></th></tr></thead>
    <tbody>
    @foreach ($khoas as $khoa)
        <tr>
            <td>{{ $khoa->ma_khoa }}</td>
            <td>{{ $khoa->ten_khoa }}</td>
            <td>{{ $khoa->email }}</td>
            <td>{{ $khoa->giang_viens_count }}</td>
            <td>{!! $khoa->active ? '<span class="badge text-bg-success">Hoạt động</span>' : '<span class="badge text-bg-secondary">Ngừng</span>' !!}</td>
            <td><a href="{{ route('admin.khoa.show', $khoa) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $khoas->links() }}
@endsection
