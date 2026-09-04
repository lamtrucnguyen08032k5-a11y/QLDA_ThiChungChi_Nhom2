@extends('layouts.app')
@section('title', 'Chi tiết Khoa')
@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                Thông tin Khoa
                <a href="{{ route('admin.khoa.edit', $khoa) }}" class="btn btn-sm btn-outline-primary">Chỉnh sửa</a>
            </div>
            <div class="card-body">
                <p><strong>Mã khoa:</strong> {{ $khoa->ma_khoa }}</p>
                <p><strong>Tên khoa:</strong> {{ $khoa->ten_khoa }}</p>
                <p><strong>Email:</strong> {{ $khoa->email }}</p>
                <p><strong>Mô tả:</strong> {{ $khoa->mo_ta ?: '—' }}</p>
                <p><strong>Trạng thái:</strong> {{ $khoa->active ? 'Hoạt động' : 'Ngừng' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">Danh sách Giảng viên</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.khoa.giangvien.store', $khoa) }}" class="row g-2 mb-3">
                    @csrf
                    <div class="col-md-3"><input name="ma_giang_vien" class="form-control form-control-sm" placeholder="Mã GV" required></div>
                    <div class="col-md-4"><input name="ho_ten" class="form-control form-control-sm" placeholder="Họ tên" required></div>
                    <div class="col-md-3"><input type="email" name="email" class="form-control form-control-sm" placeholder="Email" required></div>
                    <div class="col-md-2"><button class="btn btn-sm btn-primary w-100">Thêm GV</button></div>
                </form>
                <table class="table table-sm">
                    <thead><tr><th>Mã GV</th><th>Họ tên</th><th>Email</th><th>Trạng thái</th></tr></thead>
                    <tbody>
                    @forelse ($giangViens as $gv)
                        <tr>
                            <td>{{ $gv->ma_so }}</td>
                            <td>{{ $gv->name }}</td>
                            <td>{{ $gv->email }}</td>
                            <td>{{ $gv->active ? 'Hoạt động' : 'Khoá' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted">Chưa có Giảng viên nào.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
