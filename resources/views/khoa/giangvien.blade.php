@extends('layouts.app')
@section('title', 'Giảng viên trong Khoa')
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Thêm Giảng viên</div>
            <div class="card-body">
                <form method="POST" action="{{ route('khoa.giangvien.store') }}">
                    @csrf
                    <div class="mb-2"><input name="ma_giang_vien" class="form-control form-control-sm" placeholder="Mã GV" required></div>
                    <div class="mb-2"><input name="ho_ten" class="form-control form-control-sm" placeholder="Họ tên" required></div>
                    <div class="mb-2"><input type="email" name="email" class="form-control form-control-sm" placeholder="Email" required></div>
                    <button class="btn btn-primary btn-sm w-100">Thêm</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <table class="table table-bordered bg-white">
            <thead><tr><th>Mã GV</th><th>Họ tên</th><th>Email</th><th>Trạng thái</th></tr></thead>
            <tbody>
            @foreach ($giangViens as $gv)
                <tr><td>{{ $gv->ma_so }}</td><td>{{ $gv->name }}</td><td>{{ $gv->email }}</td><td>{{ $gv->active ? 'Hoạt động' : 'Khoá' }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
