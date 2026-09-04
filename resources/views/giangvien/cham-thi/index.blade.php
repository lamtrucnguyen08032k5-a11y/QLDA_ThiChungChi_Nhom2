@extends('layouts.app')
@section('title', 'Chấm bài thi')
@section('content')
<form class="mb-3">
    <select name="filter" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
        <option value="">-- Tất cả --</option>
        <option value="chua_cham" {{ request('filter') === 'chua_cham' ? 'selected' : '' }}>Chưa chấm xong</option>
        <option value="da_cham" {{ request('filter') === 'da_cham' ? 'selected' : '' }}>Đã chấm xong</option>
    </select>
</form>
<table class="table table-bordered bg-white">
    <thead><tr><th>Sinh viên</th><th>Kỳ thi</th><th>Nộp lúc</th><th>Điểm TN</th><th>Điểm tổng</th><th>Trạng thái</th><th></th></tr></thead>
    <tbody>
    @foreach ($baiThis as $bt)
        <tr>
            <td>{{ $bt->dangKy->sinhVien->name }} ({{ $bt->dangKy->sinhVien->ma_so }})</td>
            <td>{{ $bt->dangKy->lichThi->ten_ky_thi }}</td>
            <td>{{ $bt->gio_nop?->format('d/m/Y H:i') }}</td>
            <td>{{ $bt->diem_tu_dong }}</td>
            <td>{{ $bt->diem_tong ?? '—' }}</td>
            <td>{{ $bt->cham_xong ? 'Đã chấm xong' : 'Chưa chấm xong' }}</td>
            <td><a href="{{ route('giangvien.cham-thi.show', $bt) }}" class="btn btn-sm btn-outline-primary">Chấm bài</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $baiThis->links() }}
@endsection
