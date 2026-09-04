@extends('layouts.app')
@section('title', 'Chỉnh sửa lịch thi')
@section('content')
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('admin.lichthi.update', $lichthi) }}">
    @csrf @method('PUT')
    @include('admin.lichthi._form', ['lichthi' => $lichthi])
    <div class="mb-3">
        <label class="form-label">Trạng thái</label>
        <select name="trang_thai" class="form-select">
            @foreach (['dang_mo_dang_ky' => 'Đang mở đăng ký', 'da_dong_dang_ky' => 'Đã đóng đăng ký', 'dang_thi' => 'Đang thi', 'da_ket_thuc' => 'Đã kết thúc'] as $val => $label)
                <option value="{{ $val }}" {{ $lichthi->trang_thai === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary mt-3">Cập nhật</button>
    <a href="{{ route('admin.lichthi.index') }}" class="btn btn-outline-secondary mt-3">Hủy</a>
</form>
</div></div>
@endsection
