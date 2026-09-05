@extends('layouts.sinhvien')
@section('title', 'Trạng thái thanh toán')
@section('content')

<ul class="hvnh-steps">
    <li class="done">Bước 1: Cập nhật thông tin cá nhân</li>
    <li class="done">Bước 2: Chọn thông tin đăng ký thi</li>
    <li class="done">Bước 3: Xác nhận và thanh toán</li>
    <li class="active">Bước 4: Trạng thái thanh toán</li>
</ul>

<div class="row justify-content-center">
    <div class="col-lg-7">
        @if ($dangky->trang_thai_thanh_toan === 'da_thanh_toan')
            <div class="alert alert-success">
                <strong>✅ Thanh toán thành công!</strong> Hồ sơ đăng ký dự thi của bạn đã được ghi nhận với trạng thái
                <span class="badge badge-cho-duyet">{{ $dangky->nhanTrangThaiLabel() }}</span>.
                Một email xác nhận đã được gửi tới <strong>{{ $dangky->sinhVien->email }}</strong>.
            </div>
        @elseif ($dangky->trang_thai_thanh_toan === 'thanh_toan_that_bai')
            <div class="alert alert-danger d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span><strong>❌ Thanh toán thất bại.</strong> Hồ sơ chưa được gửi đi. Vui lòng thử lại.</span>
                <a href="{{ route('sinhvien.dangky.buoc3', $dangky) }}" class="btn btn-sm btn-primary">Thanh toán lại</a>
            </div>
        @else
            <div class="alert alert-warning">⏳ Đang chờ xử lý thanh toán...</div>
        @endif

        <div class="card mb-3">
            <div class="card-header-blue">Thông tin đăng ký</div>
            <table class="table table-kv mb-0">
                <tr><th>Mã đăng ký</th><td class="fw-bold">{{ $dangky->ma_dang_ky }}</td></tr>
                <tr><th>Bài thi</th><td>{{ $dangky->lichThi->ten_ky_thi }}</td></tr>
                <tr><th>Thời gian thi</th><td>{{ $dangky->lichThi->ngay_thi->format('d/m/Y') }} — {{ $dangky->lichThi->gio_bat_dau }}</td></tr>
                <tr><th>Địa điểm thi</th><td>{{ $dangky->lichThi->phong_thi }}</td></tr>
                <tr><th>Trạng thái hồ sơ</th><td><span class="badge badge-{{ str_replace('_','-', $dangky->trang_thai) }}">{{ $dangky->nhanTrangThaiLabel() }}</span></td></tr>
            </table>
        </div>

        <div class="card mb-4">
            <div class="card-header-blue">Thông tin thanh toán</div>
            <table class="table table-kv mb-0">
                <tr><th>Phương thức</th><td>{{ strtoupper($dangky->phuong_thuc_thanh_toan) }}</td></tr>
                <tr><th>Mã giao dịch</th><td>{{ $dangky->ma_giao_dich }}</td></tr>
                <tr><th>Số tiền</th><td class="fw-bold">{{ number_format($dangky->so_tien) }}đ</td></tr>
                <tr><th>Thời gian thanh toán</th><td>{{ optional($dangky->ngay_thanh_toan)->format('d/m/Y H:i') ?? '—' }}</td></tr>
                <tr><th>Trạng thái thanh toán</th><td><span class="badge badge-{{ str_replace('_','-', $dangky->trang_thai_thanh_toan) }}">{{ $dangky->nhanTrangThaiThanhToanLabel() }}</span></td></tr>
            </table>
        </div>

        <div class="text-end mb-5">
            <a href="{{ route('sinhvien.dangky.cua-toi') }}" class="btn btn-primary">Xem đăng ký của tôi</a>
        </div>
    </div>
</div>
@endsection
