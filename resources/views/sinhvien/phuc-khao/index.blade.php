@extends('layouts.sinhvien')
@section('title', 'Phúc khảo của tôi')
@section('content')
<table class="table table-bordered bg-white">
    <thead><tr><th>Kỳ thi</th><th>Lý do</th><th>Trạng thái</th><th>Phản hồi</th><th>Điểm sau</th></tr></thead>
    <tbody>
    @forelse ($phucKhaos as $pk)
        <tr>
            <td>{{ $pk->baiThi->dangKy->lichThi->ten_ky_thi }}</td>
            <td>{{ \Illuminate\Support\Str::limit($pk->ly_do, 50) }}</td>
            <td><span class="badge text-bg-secondary">{{ $pk->trang_thai }}</span></td>
            <td>{{ $pk->phan_hoi ?? '—' }}</td>
            <td>{{ $pk->diem_sau ?? '—' }}</td>
        </tr>
    @empty
        <tr><td colspan="5" class="text-muted">Bạn chưa gửi yêu cầu phúc khảo nào.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $phucKhaos->links() }}
@endsection
