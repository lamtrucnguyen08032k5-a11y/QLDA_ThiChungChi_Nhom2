@extends('layouts.app')
@section('title', 'Tạo đề thi')
@section('content')
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('admin.dethi.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Mã đề (VD: 01CH0001)</label>
            <input name="ma_de" class="form-control" required>
        </div>
        <div class="col-md-8">
            <label class="form-label">Tên đề thi</label>
            <input name="ten_de" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Loại chứng chỉ</label>
            <select name="loai_chung_chi" class="form-select" required>
                <option value="cntt">CNTT</option>
                <option value="tienganh">Tiếng Anh</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Khoa</label>
            <select name="khoa_id" class="form-select" required>
                @foreach ($khoas as $k)<option value="{{ $k->id }}">{{ $k->ten_khoa }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">File câu hỏi (CSV, tuỳ chọn)</label>
            <input type="file" name="file" class="form-control">
        </div>
    </div>
    <p class="small text-muted mt-2">Mẫu cột file CSV: noi_dung, loai_cau (tracnghiem/tuluan), dap_an_a, dap_an_b, dap_an_c, dap_an_d, dap_an_dung (A/B/C/D), diem.</p>
    <button class="btn btn-primary mt-2">Tạo đề thi</button>
    <a href="{{ route('admin.dethi.index') }}" class="btn btn-outline-secondary mt-2">Hủy</a>
</form>
</div></div>
@endsection
