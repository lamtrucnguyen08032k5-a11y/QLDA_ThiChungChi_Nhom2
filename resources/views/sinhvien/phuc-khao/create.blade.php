@extends('layouts.sinhvien')
@section('title', 'Yêu cầu phúc khảo')
@section('content')
<div class="card"><div class="card-body">
    <p><strong>Kỳ thi:</strong> {{ $baithi->dangKy->lichThi->ten_ky_thi }} • <strong>Điểm hiện tại:</strong> {{ $baithi->diem_tong }}</p>
    <form method="POST" action="{{ route('sinhvien.phuc-khao.store', $baithi) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Lý do yêu cầu phúc khảo</label>
            <textarea name="ly_do" class="form-control" rows="4" required minlength="10"></textarea>
        </div>
        <button class="btn btn-primary">Gửi yêu cầu</button>
    </form>
</div></div>
@endsection
