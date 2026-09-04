<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Tên kỳ thi</label>
        <input name="ten_ky_thi" class="form-control" value="{{ old('ten_ky_thi', $lichthi->ten_ky_thi ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Loại chứng chỉ</label>
        <select name="loai_chung_chi" class="form-select" required>
            <option value="cntt" {{ old('loai_chung_chi', $lichthi->loai_chung_chi ?? '') === 'cntt' ? 'selected' : '' }}>CNTT</option>
            <option value="tienganh" {{ old('loai_chung_chi', $lichthi->loai_chung_chi ?? '') === 'tienganh' ? 'selected' : '' }}>Tiếng Anh</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Khoa phụ trách chấm</label>
        <select name="khoa_id" class="form-select" required>
            @foreach ($khoas as $k)
                <option value="{{ $k->id }}" {{ (string) old('khoa_id', $lichthi->khoa_id ?? '') === (string) $k->id ? 'selected' : '' }}>{{ $k->ten_khoa }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Ngày thi</label>
        <input type="date" name="ngay_thi" class="form-control" value="{{ old('ngay_thi', isset($lichthi->ngay_thi) ? $lichthi->ngay_thi->format('Y-m-d') : '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Giờ bắt đầu</label>
        <input type="time" name="gio_bat_dau" class="form-control" value="{{ old('gio_bat_dau', $lichthi->gio_bat_dau ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Thời gian thi (phút)</label>
        <input type="number" name="thoi_gian_thi_phut" class="form-control" value="{{ old('thoi_gian_thi_phut', $lichthi->thoi_gian_thi_phut ?? 60) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Phòng thi</label>
        <input name="phong_thi" class="form-control" value="{{ old('phong_thi', $lichthi->phong_thi ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Số lượng tối đa</label>
        <input type="number" name="so_luong_toi_da" class="form-control" value="{{ old('so_luong_toi_da', $lichthi->so_luong_toi_da ?? 50) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Hạn đăng ký</label>
        <input type="datetime-local" name="han_dang_ky" class="form-control" value="{{ old('han_dang_ky', isset($lichthi->han_dang_ky) ? $lichthi->han_dang_ky->format('Y-m-d\TH:i') : '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Lệ phí (đ)</label>
        <input type="number" name="le_phi" class="form-control" value="{{ old('le_phi', $lichthi->le_phi ?? 0) }}" required>
    </div>
</div>
