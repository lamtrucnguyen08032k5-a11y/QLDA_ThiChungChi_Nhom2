@extends('layouts.app')
@section('title', 'Chứng nhận')
@section('content')
<form class="mb-3">
    <select name="trang_thai" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
        <option value="">-- Tất cả trạng thái --</option>
        <option value="cho_duyet" {{ request('trang_thai') === 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
        <option value="da_cap" {{ request('trang_thai') === 'da_cap' ? 'selected' : '' }}>Đã cấp</option>
        <option value="tu_choi" {{ request('trang_thai') === 'tu_choi' ? 'selected' : '' }}>Từ chối</option>
    </select>
</form>
<table class="table table-bordered bg-white">
    <thead><tr><th>Sinh viên</th><th>Đề thi</th><th>Địa chỉ nhận</th><th>SĐT</th><th>Số chứng nhận</th><th>Trạng thái</th><th></th></tr></thead>
    <tbody>
    @foreach ($chungNhans as $cn)
        <tr>
            <td>{{ $cn->sinhVien->name }} ({{ $cn->sinhVien->ma_so }})</td>
            <td>{{ $cn->baiThi->deThi->ten_de }}</td>
            <td>{{ $cn->dia_chi_nhan }}</td>
            <td>{{ $cn->so_dien_thoai }}</td>
            <td>{{ $cn->so_chung_nhan ?? '—' }}</td>
            <td><span class="badge text-bg-secondary">{{ $cn->trang_thai }}</span></td>
            <td class="text-nowrap">
                @if ($cn->trang_thai === 'cho_duyet')
                    <form method="POST" action="{{ route('admin.chungnhan.cap', $cn) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-success">Cấp chứng nhận</button>
                    </form>
                    <form method="POST" action="{{ route('admin.chungnhan.tuchoi', $cn) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Từ chối</button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $chungNhans->links() }}
@endsection
