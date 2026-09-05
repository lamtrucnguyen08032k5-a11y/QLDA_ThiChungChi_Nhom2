@extends('layouts.app')
@section('title', 'Danh sách đăng ký thi')
@section('content')
<div class="card mb-3"><div class="card-body">
    <h5>{{ $lichthi->ten_ky_thi }}</h5>
    <p class="text-muted mb-0">{{ $lichthi->ngay_thi->format('d/m/Y') }} • Phòng {{ $lichthi->phong_thi }} • Mã ca thi <code>{{ $lichthi->ma_ca_thi }}</code> • Tối đa {{ $lichthi->so_luong_toi_da }} thí sinh</p>
</div></div>

<form class="mb-3 d-flex gap-2">
    <select name="trang_thai" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
        <option value="">-- Tất cả trạng thái --</option>
        <option value="cho_duyet" {{ request('trang_thai') === 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
        <option value="cho_bo_sung" {{ request('trang_thai') === 'cho_bo_sung' ? 'selected' : '' }}>Yêu cầu bổ sung</option>
        <option value="da_duyet" {{ request('trang_thai') === 'da_duyet' ? 'selected' : '' }}>Đã duyệt</option>
        <option value="tu_choi" {{ request('trang_thai') === 'tu_choi' ? 'selected' : '' }}>Từ chối</option>
        <option value="da_huy" {{ request('trang_thai') === 'da_huy' ? 'selected' : '' }}>Đã huỷ</option>
    </select>
</form>

<table class="table table-bordered bg-white">
    <thead><tr><th>Mã SV</th><th>Họ tên</th><th>Email</th><th>Lớp</th><th>Trạng thái</th><th>Ngày đăng ký</th><th></th></tr></thead>
    <tbody>
    @foreach ($dangKys as $dk)
        <tr>
            <td>{{ $dk->sinhVien->ma_so }}</td>
            <td>{{ $dk->sinhVien->name }}</td>
            <td>{{ $dk->sinhVien->email }}</td>
            <td>{{ $dk->sinhVien->lop }}</td>
            <td><span class="badge text-bg-secondary">{{ $dk->trang_thai }}</span></td>
            <td>{{ $dk->created_at->format('d/m/Y H:i') }}</td>
            <td class="text-nowrap">
                @if ($dk->trang_thai === 'cho_duyet')
                    <form method="POST" action="{{ route('admin.dangky.approve', [$lichthi, $dk]) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-success">Duyệt</button>
                    </form>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#boSung{{ $dk->id }}">Yêu cầu bổ sung</button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#tuChoi{{ $dk->id }}">Từ chối</button>
                    <div class="modal fade" id="tuChoi{{ $dk->id }}">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.dangky.reject', [$lichthi, $dk]) }}" class="modal-content">
                                @csrf
                                <div class="modal-body">
                                    <label class="form-label">Lý do từ chối</label>
                                    <textarea name="ly_do_tu_choi" class="form-control" required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button class="btn btn-danger">Xác nhận từ chối</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal fade" id="boSung{{ $dk->id }}">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.dangky.bosung', [$lichthi, $dk]) }}" class="modal-content">
                                @csrf
                                <div class="modal-body">
                                    <label class="form-label">Các trường cần bổ sung</label>
                                    @foreach (['so_dien_thoai'=>'Số điện thoại','ngay_sinh'=>'Ngày sinh','gioi_tinh'=>'Giới tính','dan_toc'=>'Dân tộc','noi_sinh'=>'Nơi sinh','so_cccd'=>'Số CCCD','anh_cccd_truoc'=>'Ảnh CCCD mặt trước','anh_cccd_sau'=>'Ảnh CCCD mặt sau','anh_ho_so'=>'Ảnh hồ sơ dự thi 4x6','anh_the_sv'=>'Ảnh thẻ sinh viên'] as $key => $nhan)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="truong_can_bo_sung[]" value="{{ $key }}" id="ts_{{ $dk->id }}_{{ $key }}">
                                            <label class="form-check-label small" for="ts_{{ $dk->id }}_{{ $key }}">{{ $nhan }}</label>
                                        </div>
                                    @endforeach
                                    <label class="form-label mt-2">Lý do / nội dung cần bổ sung</label>
                                    <textarea name="ly_do_bo_sung" class="form-control" required></textarea>
                                    <label class="form-label mt-2">Thời hạn bổ sung trực tuyến</label>
                                    <input type="datetime-local" name="han_bo_sung" class="form-control" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button class="btn btn-warning">Gửi yêu cầu bổ sung</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $dangKys->links() }}
@endsection
