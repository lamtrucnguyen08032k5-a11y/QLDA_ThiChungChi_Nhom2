@extends('layouts.app')
@section('title', 'Kho email Sinh viên (danh sách hợp lệ để đăng ký)')
@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">Thêm 1 sinh viên</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.svwhitelist.store') }}">
                    @csrf
                    <div class="mb-2"><input name="ma_sv" class="form-control form-control-sm" placeholder="Mã SV" required></div>
                    <div class="mb-2"><input name="ho_ten" class="form-control form-control-sm" placeholder="Họ tên" required></div>
                    <div class="mb-2"><input type="email" name="email" class="form-control form-control-sm" placeholder="Email trường" required></div>
                    <div class="mb-2"><input name="lop" class="form-control form-control-sm" placeholder="Lớp"></div>
                    <div class="mb-2"><input name="khoa_hoc" class="form-control form-control-sm" placeholder="Khoá học (VD: K23)"></div>
                    <button class="btn btn-primary btn-sm w-100">Thêm</button>
                </form>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">Import hàng loạt (CSV)</div>
            <div class="card-body">
                <p class="small text-muted">Mẫu cột: ma_sv, ho_ten, email, lop, khoa_hoc (dòng đầu là tiêu đề).</p>
                <form method="POST" action="{{ route('admin.svwhitelist.import') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control form-control-sm mb-2" required>
                    <button class="btn btn-outline-primary btn-sm w-100">Import</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <form class="d-flex gap-2">
                    <input name="search" class="form-control form-control-sm" placeholder="Tìm mã SV / tên / email" value="{{ request('search') }}">
                    <button class="btn btn-sm btn-outline-secondary">Tìm</button>
                </form>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Mã SV</th><th>Họ tên</th><th>Email</th><th>Lớp</th><th>Đã đăng ký?</th><th></th></tr></thead>
                    <tbody>
                    @foreach ($sinhViens as $sv)
                        <tr>
                            <td>{{ $sv->ma_sv }}</td>
                            <td>{{ $sv->ho_ten }}</td>
                            <td>{{ $sv->email }}</td>
                            <td>{{ $sv->lop }}</td>
                            <td>{{ $sv->da_dang_ky ? 'Rồi' : 'Chưa' }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.svwhitelist.destroy', $sv) }}" onsubmit="return confirm('Xoá sinh viên này khỏi danh sách?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $sinhViens->links() }}</div>
        </div>
    </div>
</div>
@endsection
