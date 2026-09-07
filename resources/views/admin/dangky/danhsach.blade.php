@extends('layouts.app')
@section('title', 'Danh sách đăng ký thi')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 text-primary fw-bold">Danh sách các lịch thi có sinh viên đăng ký</h4>
</div>

<!-- Thanh tìm kiếm & bộ lọc -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.dangky.danhsach') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Tìm theo tên bài thi, mã ca thi, phòng thi..." value="{{ request('q') }}">
            </div>
            <div class="col-md-3">
                <select name="trang_thai" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">-- Tất cả trạng thái ca thi --</option>
                    <option value="dang_mo_dang_ky" {{ request('trang_thai') === 'dang_mo_dang_ky' ? 'selected' : '' }}>Đang mở đăng ký</option>
                    <option value="da_dong_dang_ky" {{ request('trang_thai') === 'da_dong_dang_ky' ? 'selected' : '' }}>Đã đóng đăng ký</option>
                    <option value="dang_thi" {{ request('trang_thai') === 'dang_thi' ? 'selected' : '' }}>Đang thi</option>
                    <option value="da_ket_thuc" {{ request('trang_thai') === 'da_ket_thuc' ? 'selected' : '' }}>Đã kết thúc</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-primary w-100">Tìm kiếm</button>
            </div>
            @if(request()->anyFilled(['q', 'trang_thai']))
                <div class="col-md-2">
                    <a href="{{ route('admin.dangky.danhsach') }}" class="btn btn-sm btn-outline-secondary w-100">Xóa bộ lọc</a>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Tên bài thi / Kỳ thi</th>
                        <th>Ngày thi</th>
                        <th>Ca thi</th>
                        <th>Địa điểm / Phòng thi</th>
                        <th class="text-center">Số lượng SV đăng ký</th>
                        <th class="text-center">Số hồ sơ chờ duyệt</th>
                        <th class="text-center">Trạng thái lịch thi</th>
                        <th class="text-end pe-3">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($lichThis as $lt)
                    <tr>
                        <td class="ps-3 fw-semibold">
                            <div>{{ $lt->ten_ky_thi }}</div>
                            <small class="text-muted">{{ strtoupper($lt->loai_chung_chi) }} — {{ optional($lt->khoa)->ten_khoa ?? 'Toàn trường' }}</small>
                        </td>
                        <td>{{ optional($lt->ngay_thi)->format('d/m/Y') }}</td>
                        <td>
                            <code>{{ $lt->ma_ca_thi }}</code>
                            @if($lt->gio_bat_dau)
                                <span class="small text-muted">({{ $lt->gio_bat_dau }})</span>
                            @endif
                        </td>
                        <td><span class="badge text-bg-light border text-dark">{{ $lt->phong_thi }}</span></td>
                        <td class="text-center">
                            <span class="fw-bold text-primary">{{ $lt->so_luong_dang_ky }}</span>
                            <span class="text-muted">/ {{ $lt->so_luong_toi_da }}</span>
                        </td>
                        <td class="text-center">
                            @if ($lt->so_ho_so_cho_duyet > 0)
                                <span class="badge bg-warning text-dark px-2 py-1 fs-6">{{ $lt->so_ho_so_cho_duyet }} hồ sơ</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">0</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($lt->trang_thai === 'dang_mo_dang_ky')
                                <span class="badge bg-success">Đang mở đăng ký</span>
                            @elseif ($lt->trang_thai === 'da_dong_dang_ky')
                                <span class="badge bg-secondary">Đã đóng đăng ký</span>
                            @elseif ($lt->trang_thai === 'dang_thi')
                                <span class="badge bg-info text-dark">Đang thi</span>
                            @else
                                <span class="badge bg-dark">Đã kết thúc</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <a href="{{ route('admin.dangky.index', $lt) }}" class="btn btn-sm btn-outline-primary">
                                Xem danh sách đăng ký ({{ $lt->so_luong_dang_ky }})
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Không tìm thấy lịch thi nào có đăng ký.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $lichThis->links() }}
</div>

@endsection
