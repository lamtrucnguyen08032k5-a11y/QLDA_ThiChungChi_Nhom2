@extends('layouts.sinhvien')

@section('title', 'Đăng ký dự thi')

@section('content')
@php
    $hideErrorSummary = true;
    $u = $u ?? auth()->user();
@endphp

<ul class="hvnh-steps">
    <li class="active">Bước 1: Cập nhật thông tin cá nhân</li>
    <li>Bước 2: Chọn thông tin đăng ký thi</li>
    <li>Bước 3: Xác nhận và thanh toán</li>
    <li>Bước 4: Trạng thái thanh toán</li>
</ul>

<form method="POST" action="{{ route('sinhvien.dangky.buoc1.luu', $lichthi) }}" enctype="multipart/form-data" novalidate>
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
                            <label class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" name="ngay_sinh" class="form-control @error('ngay_sinh') is-invalid @enderror" value="{{ old('ngay_sinh', $draft['ngay_sinh'] ?? '') }}">
                            @error('ngay_sinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label d-block">Giới tính <span class="text-danger">*</span></label>
                            @php $gt = old('gioi_tinh', $draft['gioi_tinh'] ?? ''); @endphp
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('gioi_tinh') is-invalid @enderror" type="radio" name="gioi_tinh" value="nam" id="gt_nam" {{ $gt === 'nam' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gt_nam">Nam</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('gioi_tinh') is-invalid @enderror" type="radio" name="gioi_tinh" value="nu" id="gt_nu" {{ $gt === 'nu' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gt_nu">Nữ</label>
                            </div>
                            @error('gioi_tinh')<div class="small text-danger mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dân tộc <span class="text-danger">*</span></label>
                            <input type="text" name="dan_toc" class="form-control @error('dan_toc') is-invalid @enderror" value="{{ old('dan_toc', $draft['dan_toc'] ?? 'Kinh') }}">
                            @error('dan_toc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nơi sinh <span class="text-danger">*</span></label>
                            <input type="text" name="noi_sinh" class="form-control @error('noi_sinh') is-invalid @enderror" value="{{ old('noi_sinh', $draft['noi_sinh'] ?? '') }}">
                            @error('noi_sinh')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-12"><hr></div>
                        <div class="col-12"><h6 class="text-primary">Thông tin pháp lý</h6></div>
                        <div class="col-md-6">
                            <label class="form-label">Số CCCD <span class="text-danger">*</span></label>
                            <input type="text" name="so_cccd" class="form-control @error('so_cccd') is-invalid @enderror" value="{{ old('so_cccd', $draft['so_cccd'] ?? '') }}">
                            @error('so_cccd')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header-blue d-flex justify-content-between align-items-center">
                    <span>Thông tin liên hệ</span>
                </div>
                <div class="card-body">
                    <p class="small text-danger mb-3">Thí sinh vui lòng điền theo địa chỉ sau khi thay đổi địa giới hành chính.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                            <select id="select_tinh" name="tinh_thanh_pho" class="form-select @error('tinh_thanh_pho') is-invalid @enderror" data-old-value="{{ old('tinh_thanh_pho', $draft['tinh_thanh_pho_code'] ?? '') }}">
                                <option value="">Chọn tỉnh/thành phố</option>
                            </select>
                            @error('tinh_thanh_pho')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Xã/Phường <span class="text-danger">*</span></label>
                            <select id="select_xa" name="xa_phuong" class="form-select @error('xa_phuong') is-invalid @enderror" data-old-value="{{ old('xa_phuong', $draft['xa_phuong_code'] ?? '') }}" disabled>
                                <option value="">Chọn xã/phường</option>
                            </select>
                            @error('xa_phuong')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số nhà, đường/phố <span class="text-danger">*</span></label>
                            <input type="text" name="dia_chi_chi_tiet" class="form-control @error('dia_chi_chi_tiet') is-invalid @enderror" value="{{ old('dia_chi_chi_tiet', $draft['dia_chi_chi_tiet'] ?? '') }}">
                            @error('dia_chi_chi_tiet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="so_dien_thoai" class="form-control @error('so_dien_thoai') is-invalid @enderror" value="{{ old('so_dien_thoai', $draft['so_dien_thoai'] ?? '') }}">
                            @error('so_dien_thoai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Email liên hệ <span class="text-danger">*</span></label>
                            <input type="email" name="email_lien_he" class="form-control @error('email_lien_he') is-invalid @enderror" value="{{ old('email_lien_he', $draft['email_lien_he'] ?? '') }}" placeholder="Email khác để nhận thông báo (có thể khác email trường)">
                            @error('email_lien_he')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Email này dùng để liên hệ khi cần, có thể khác với email tài khoản ({{ $u->email }}).</div>
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

                    {{-- ẢNH THẺ (chân dung 3x4) --}}
                    <label class="form-label fw-bold mb-1">Ảnh thẻ (Kích thước 3x4cm) <span class="text-danger">*</span></label>
                    <p class="small text-muted mb-1">Vui lòng tuân thủ các yêu cầu tiêu chuẩn về ảnh.</p>
                    <p class="small text-danger mb-2">Lưu ý*: Ảnh đeo kính được coi là không đáp ứng tiêu chuẩn. Nếu ảnh không đáp ứng tiêu chuẩn, thí sinh có thể bị từ chối tham dự kỳ thi.</p>

                    <div class="hvnh-anh-preview mb-1 {{ empty($draft['anh_ho_so']) ? '' : 'has-img' }}" id="preview_box_anh_ho_so">
                        <img id="preview_anh_ho_so" src="{{ !empty($draft['anh_ho_so']) ? \Illuminate\Support\Facades\Storage::url($draft['anh_ho_so']) : '' }}" class="{{ empty($draft['anh_ho_so']) ? 'd-none' : '' }}" alt="Xem trước ảnh thẻ">
                        <span class="placeholder-text {{ empty($draft['anh_ho_so']) ? '' : 'd-none' }}">Chưa có ảnh — bấm chọn tệp bên dưới</span>
                    </div>
                    <input type="file" name="anh_ho_so" id="input_anh_ho_so" data-preview="preview_anh_ho_so" accept="image/*" class="form-control mb-3 @error('anh_ho_so') is-invalid @enderror">
                    @error('anh_ho_so')<div class="invalid-feedback d-block mt-n2 mb-3">{{ $message }}</div>@enderror

                    <hr>

                    {{-- CCCD --}}
                    <label class="form-label fw-bold mb-1">Giấy tờ tuỳ thân (CCCD/Hộ chiếu) <span class="text-danger">*</span></label>
                    <p class="small text-muted mb-2">Giấy tờ cần còn hạn và nguyên gốc, không bong/rách/nứt/gãy, các chi tiết rõ nét, không có dấu hiệu chỉnh sửa.</p>

                    <label class="form-label small text-muted mb-1">Mặt trước</label>
                    <div class="hvnh-anh-preview mb-1 {{ empty($draft['anh_cccd_truoc']) ? '' : 'has-img' }}" id="preview_box_anh_cccd_truoc">
                        <img id="preview_anh_cccd_truoc" src="{{ !empty($draft['anh_cccd_truoc']) ? \Illuminate\Support\Facades\Storage::url($draft['anh_cccd_truoc']) : '' }}" class="{{ empty($draft['anh_cccd_truoc']) ? 'd-none' : '' }}" alt="Xem trước CCCD mặt trước">
                        <span class="placeholder-text {{ empty($draft['anh_cccd_truoc']) ? '' : 'd-none' }}">Chưa có ảnh</span>
                    </div>
                    <input type="file" name="anh_cccd_truoc" id="input_anh_cccd_truoc" data-preview="preview_anh_cccd_truoc" accept="image/*" class="form-control mb-1 @error('anh_cccd_truoc') is-invalid @enderror">
                    @if ($errors->has('anh_cccd_truoc'))
                        <div class="invalid-feedback d-block mb-3">{{ $errors->first('anh_cccd_truoc') }}</div>
                    @else
                        <div class="mb-3"></div>
                    @endif

                    <label class="form-label small text-muted mb-1">Mặt sau</label>
                    <div class="hvnh-anh-preview mb-1 {{ empty($draft['anh_cccd_sau']) ? '' : 'has-img' }}" id="preview_box_anh_cccd_sau">
                        <img id="preview_anh_cccd_sau" src="{{ !empty($draft['anh_cccd_sau']) ? \Illuminate\Support\Facades\Storage::url($draft['anh_cccd_sau']) : '' }}" class="{{ empty($draft['anh_cccd_sau']) ? 'd-none' : '' }}" alt="Xem trước CCCD mặt sau">
                        <span class="placeholder-text {{ empty($draft['anh_cccd_sau']) ? '' : 'd-none' }}">Chưa có ảnh</span>
                    </div>
                    <input type="file" name="anh_cccd_sau" id="input_anh_cccd_sau" data-preview="preview_anh_cccd_sau" accept="image/*" class="form-control mb-1 @error('anh_cccd_sau') is-invalid @enderror">
                    @if ($errors->has('anh_cccd_sau'))
                        <div class="invalid-feedback d-block mb-3">{{ $errors->first('anh_cccd_sau') }}</div>
                    @else
                        <div class="mb-3"></div>
                    @endif

                    <hr>

                    {{-- Thẻ SV (khong bat buoc) --}}
                    <label class="form-label mb-1">Ảnh thẻ sinh viên <span class="text-muted small">(không bắt buộc)</span></label>
                    <div class="hvnh-anh-preview mb-1 {{ empty($draft['anh_the_sv']) ? '' : 'has-img' }}" id="preview_box_anh_the_sv">
                        <img id="preview_anh_the_sv" src="{{ !empty($draft['anh_the_sv']) ? \Illuminate\Support\Facades\Storage::url($draft['anh_the_sv']) : '' }}" class="{{ empty($draft['anh_the_sv']) ? 'd-none' : '' }}" alt="Xem trước thẻ sinh viên">
                        <span class="placeholder-text {{ empty($draft['anh_the_sv']) ? '' : 'd-none' }}">Chưa có ảnh</span>
                    </div>
                    <input type="file" name="anh_the_sv" id="input_anh_the_sv" data-preview="preview_anh_the_sv" accept="image/*" class="form-control @error('anh_the_sv') is-invalid @enderror">
                    @error('anh_the_sv')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

@section('scripts')
<script>
document.querySelectorAll('input[type=file][data-preview]').forEach(function (input) {
    input.addEventListener('change', function (e) {
        var file = e.target.files && e.target.files[0];
        var img = document.getElementById(input.dataset.preview);
        var box = document.getElementById('preview_box_' + input.dataset.preview.replace('preview_', ''));
        if (!file || !img) return;
        var url = URL.createObjectURL(file);
        img.src = url;
        img.classList.remove('d-none');
        if (box) {
            box.classList.add('has-img');
            var placeholder = box.querySelector('.placeholder-text');
            if (placeholder) placeholder.classList.add('d-none');
        }
    });
});

// Cascading dropdown Tỉnh/Thành phố -> Xã/Phường, dữ liệu tĩnh 34 tỉnh/thành sau sáp nhập 2025
(function () {
    var selectTinh = document.getElementById('select_tinh');
    var selectXa = document.getElementById('select_xa');
    if (!selectTinh || !selectXa) return;

    var oldTinh = selectTinh.dataset.oldValue || '';
    var oldXa = selectXa.dataset.oldValue || '';

    fetch('{{ asset("data/vn-address.json") }}')
        .then(function (res) { return res.json(); })
        .then(function (data) {
            data.forEach(function (tinh) {
                var opt = document.createElement('option');
                opt.value = tinh.c;
                opt.textContent = tinh.n;
                if (tinh.c === oldTinh) opt.selected = true;
                selectTinh.appendChild(opt);
            });

            function napXaPhuong(maTinh, maXaChon) {
                selectXa.innerHTML = '<option value="">Chọn xã/phường</option>';
                var tinh = data.find(function (t) { return t.c === maTinh; });
                if (!tinh) { selectXa.disabled = true; return; }
                tinh.w.forEach(function (xa) {
                    var opt = document.createElement('option');
                    opt.value = xa.c;
                    opt.textContent = xa.n;
                    if (xa.c === maXaChon) opt.selected = true;
                    selectXa.appendChild(opt);
                });
                selectXa.disabled = false;
            }

            if (oldTinh) napXaPhuong(oldTinh, oldXa);

            selectTinh.addEventListener('change', function () {
                napXaPhuong(this.value, null);
            });
        })
        .catch(function () {
            selectTinh.innerHTML = '<option value="">Không tải được danh sách tỉnh/thành</option>';
        });
})();
</script>
@endsection
