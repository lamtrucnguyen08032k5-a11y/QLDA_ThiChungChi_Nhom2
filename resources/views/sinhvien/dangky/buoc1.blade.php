@extends('layouts.sinhvien')
@section('title', 'Đăng ký dự thi')
@section('content')
@php $u = auth()->user(); @endphp

<ul class="hvnh-steps">
    <li class="active">Bước 1: Cập nhật thông tin cá nhân</li>
    <li>Bước 2: Chọn thông tin đăng ký thi</li>
    <li>Bước 3: Xác nhận và thanh toán</li>
    <li>Bước 4: Trạng thái thanh toán</li>
</ul>

<form method="POST" action="{{ route('sinhvien.dangky.buoc1.luu', $lichthi) }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header-blue d-flex justify-content-between align-items-center">
                    <span>Thông tin cá nhân</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Họ và tên thí sinh</label>
                            <input type="text" class="form-control" value="{{ $u->name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mã sinh viên</label>
                            <input type="text" class="form-control" value="{{ $u->ma_so }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Lớp</label>
                            <input type="text" class="form-control" value="{{ $u->lop }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Khoa</label>
                            <input type="text" class="form-control" value="{{ optional($u->khoa)->ten_khoa }}" disabled>
                        </div>

                        <div class="col-12"><hr></div>

                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="so_dien_thoai" class="form-control" value="{{ old('so_dien_thoai', $draft['so_dien_thoai'] ?? '') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" name="ngay_sinh" class="form-control" value="{{ old('ngay_sinh', $draft['ngay_sinh'] ?? '') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label d-block">Giới tính <span class="text-danger">*</span></label>
                            @php $gt = old('gioi_tinh', $draft['gioi_tinh'] ?? ''); @endphp
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gioi_tinh" value="nam" id="gt_nam" {{ $gt === 'nam' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="gt_nam">Nam</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gioi_tinh" value="nu" id="gt_nu" {{ $gt === 'nu' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gt_nu">Nữ</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Dân tộc <span class="text-danger">*</span></label>
                            <input type="text" name="dan_toc" class="form-control" value="{{ old('dan_toc', $draft['dan_toc'] ?? 'Kinh') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nơi sinh <span class="text-danger">*</span></label>
                            <input type="text" name="noi_sinh" class="form-control" value="{{ old('noi_sinh', $draft['noi_sinh'] ?? '') }}" required>
                        </div>

                        <div class="col-12"><hr></div>
                        <div class="col-12"><h6 class="text-primary">Thông tin pháp lý</h6></div>
                        <div class="col-md-6">
                            <label class="form-label">Số CCCD <span class="text-danger">*</span></label>
                            <input type="text" name="so_cccd" class="form-control" value="{{ old('so_cccd', $draft['so_cccd'] ?? '') }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header-blue">Thông tin ca thi (tự động, không thể chỉnh sửa)</div>
                <div class="card-body">
                    <div class="row g-2 small">
                        <div class="col-md-6"><strong>Bài thi:</strong> {{ $lichthi->ten_ky_thi }}</div>
                        <div class="col-md-3"><strong>Ngày thi:</strong> {{ $lichthi->ngay_thi->format('d/m/Y') }}</div>
                        <div class="col-md-3"><strong>Ca thi:</strong> {{ $lichthi->ma_ca_thi }}</div>
                        <div class="col-md-6"><strong>Địa điểm/phòng thi:</strong> {{ $lichthi->phong_thi }}</div>
                        <div class="col-md-6"><strong>Lệ phí:</strong> {{ number_format($lichthi->le_phi) }}đ</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header-blue">Ảnh hồ sơ dự thi</div>
                <div class="card-body">
                    <label class="form-label">Ảnh hồ sơ dự thi (4x6) <span class="text-danger">*</span></label>
                    <input type="file" name="anh_ho_so" accept="image/*" class="form-control mb-1">
                    @if(!empty($draft['anh_ho_so']))<div class="small text-success mb-2">✓ Đã tải lên</div>@endif
                    <div class="form-text mb-3">Ảnh chân dung nền trắng/xanh, không đeo kính.</div>

                    <label class="form-label">Ảnh CCCD mặt trước <span class="text-danger">*</span></label>
                    <input type="file" name="anh_cccd_truoc" accept="image/*" class="form-control mb-1">
                    @if(!empty($draft['anh_cccd_truoc']))<div class="small text-success mb-3">✓ Đã tải lên</div>@else<div class="mb-3"></div>@endif

                    <label class="form-label">Ảnh CCCD mặt sau <span class="text-danger">*</span></label>
                    <input type="file" name="anh_cccd_sau" accept="image/*" class="form-control mb-1">
                    @if(!empty($draft['anh_cccd_sau']))<div class="small text-success mb-3">✓ Đã tải lên</div>@else<div class="mb-3"></div>@endif

                    <label class="form-label">Ảnh thẻ sinh viên (không bắt buộc)</label>
                    <input type="file" name="anh_the_sv" accept="image/*" class="form-control mb-1">
                    @if(!empty($draft['anh_the_sv']))<div class="small text-success">✓ Đã tải lên</div>@endif

                    <div class="form-text mt-2">Giấy tờ cần còn hạn, nguyên gốc, không bong/rách/nhoè.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3 mb-5">
        <a href="{{ route('sinhvien.dangky.index') }}" class="btn btn-outline-secondary">Huỷ</a>
        <button class="btn btn-primary px-4">Tiếp tục</button>
    </div>
</form>
@endsection
