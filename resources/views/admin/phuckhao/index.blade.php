@extends('layouts.app')
@section('title', 'Phúc khảo')
@section('content')
<form class="mb-3">
    <select name="trang_thai" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
        <option value="">-- Tất cả trạng thái --</option>
        <option value="cho_xu_ly" {{ request('trang_thai') === 'cho_xu_ly' ? 'selected' : '' }}>Chờ xử lý</option>
        <option value="da_xu_ly" {{ request('trang_thai') === 'da_xu_ly' ? 'selected' : '' }}>Đã xử lý</option>
        <option value="tu_choi" {{ request('trang_thai') === 'tu_choi' ? 'selected' : '' }}>Từ chối</option>
    </select>
</form>
<table class="table table-bordered bg-white">
    <thead><tr><th>Sinh viên</th><th>Bài thi (Khoa)</th><th>Lý do</th><th>Trạng thái</th><th>Điểm trước/sau</th><th></th></tr></thead>
    <tbody>
    @foreach ($phucKhaos as $pk)
        <tr>
            <td>{{ $pk->baiThi->dangKy->sinhVien->name }}</td>
            <td>{{ $pk->baiThi->deThi->khoa->ten_khoa }}</td>
            <td>{{ \Illuminate\Support\Str::limit($pk->ly_do, 50) }}</td>
            <td><span class="badge text-bg-secondary">{{ $pk->trang_thai }}</span></td>
            <td>{{ $pk->diem_truoc ?? '—' }} / {{ $pk->diem_sau ?? '—' }}</td>
            <td><a href="{{ route('admin.phuckhao.show', $pk) }}" class="btn btn-sm btn-outline-primary">Xem</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $phucKhaos->links() }}
@endsection
