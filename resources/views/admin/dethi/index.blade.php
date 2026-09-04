@extends('layouts.app')
@section('title', 'Kho đề thi')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h5>Kho đề thi</h5>
    <a href="{{ route('admin.dethi.create') }}" class="btn btn-primary btn-sm">+ Tạo đề thi</a>
</div>
<table class="table table-bordered bg-white">
    <thead><tr><th>Mã đề</th><th>Tên đề</th><th>Loại</th><th>Khoa</th><th>Số câu hỏi</th><th></th></tr></thead>
    <tbody>
    @foreach ($deThis as $de)
        <tr>
            <td><code>{{ $de->ma_de }}</code></td>
            <td>{{ $de->ten_de }}</td>
            <td>{{ $de->loai_chung_chi === 'cntt' ? 'CNTT' : 'Tiếng Anh' }}</td>
            <td>{{ $de->khoa->ten_khoa }}</td>
            <td>{{ $de->cau_hois_count }}</td>
            <td><a href="{{ route('admin.dethi.show', $de) }}" class="btn btn-sm btn-outline-primary">Quản lý câu hỏi</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $deThis->links() }}
@endsection
