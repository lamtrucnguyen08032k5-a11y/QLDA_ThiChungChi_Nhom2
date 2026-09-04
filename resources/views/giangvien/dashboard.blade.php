@extends('layouts.app')
@section('title', 'Tổng quan Giảng viên')
@section('content')
<div class="row g-3">
    <div class="col-md-6">
        <div class="card text-bg-warning"><div class="card-body">
            <div class="small">Bài cần chấm</div><div class="fs-3 fw-bold">{{ $baiCanCham }}</div>
            <a href="{{ route('giangvien.cham-thi.index') }}" class="btn btn-sm btn-light mt-2">Chấm bài ngay</a>
        </div></div>
    </div>
    <div class="col-md-6">
        <div class="card text-bg-danger"><div class="card-body">
            <div class="small">Phúc khảo chờ xử lý</div><div class="fs-3 fw-bold">{{ $phucKhaoCho }}</div>
            <a href="{{ route('giangvien.phuc-khao.index') }}" class="btn btn-sm btn-light mt-2">Xử lý phúc khảo</a>
        </div></div>
    </div>
</div>
@endsection
