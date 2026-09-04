@extends('layouts.app')
@section('title', 'Đăng ký thi')
@section('content')
<form class="mb-3">
    <select name="loai_chung_chi" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
        <option value="">-- Tất cả loại chứng chỉ --</option>
        <option value="cntt" {{ request('loai_chung_chi') === 'cntt' ? 'selected' : '' }}>CNTT</option>
        <option value="tienganh" {{ request('loai_chung_chi') === 'tienganh' ? 'selected' : '' }}>Tiếng Anh</option>
    </select>
</form>
<div class="row g-3">
    @forelse ($lichThis as $lt)
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6>{{ $lt->ten_ky_thi }}</h6>
                    <p class="small text-muted mb-1">{{ $lt->loai_chung_chi === 'cntt' ? 'CNTT' : 'Tiếng Anh' }} • Ngày thi: {{ $lt->ngay_thi->format('d/m/Y') }} • Phòng {{ $lt->phong_thi }}</p>
                    <p class="small mb-2">Hạn đăng ký: {{ $lt->han_dang_ky->format('d/m/Y H:i') }} • Lệ phí: {{ number_format($lt->le_phi) }}đ</p>
                    @if (in_array($lt->id, $daDangKyIds))
                        <span class="badge text-bg-secondary">Đã đăng ký</span>
                    @else
                        <form method="POST" action="{{ route('sinhvien.dangky.store', $lt) }}">
                            @csrf
                            <button class="btn btn-primary btn-sm">Đăng ký thi</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">Hiện không có ca thi nào đang mở đăng ký.</p>
    @endforelse
</div>
{{ $lichThis->links() }}
@endsection
