@extends('layouts.app')
@section('title', 'Thêm mới Khoa')
@section('content')
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('admin.khoa.store') }}">
    @csrf
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Mã khoa</label>
            <input name="ma_khoa" class="form-control" value="{{ old('ma_khoa') }}" required>
        </div>
        <div class="col-md-8">
            <label class="form-label">Tên khoa</label>
            <input name="ten_khoa" class="form-control" value="{{ old('ten_khoa') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Email Khoa</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="col-md-12">
            <label class="form-label">Mô tả</label>
            <textarea name="mo_ta" class="form-control">{{ old('mo_ta') }}</textarea>
        </div>
    </div>
    <button class="btn btn-primary mt-3">Lưu</button>
    <a href="{{ route('admin.khoa.index') }}" class="btn btn-outline-secondary mt-3">Hủy</a>
</form>
</div></div>
@endsection
