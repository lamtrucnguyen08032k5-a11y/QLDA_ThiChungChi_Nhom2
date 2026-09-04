@extends('layouts.app')
@section('title', 'Đăng ký của tôi')
@section('content')
<table class="table table-bordered bg-white">
    <thead><tr><th>Kỳ thi</th><th>Ngày thi</th><th>Trạng thái</th><th>Lý do từ chối</th><th></th></tr></thead>
    <tbody>
    @foreach ($dangKys as $dk)
        <tr>
            <td>{{ $dk->lichThi->ten_ky_thi }}</td>
            <td>{{ $dk->lichThi->ngay_thi->format('d/m/Y') }}</td>
            <td><span class="badge text-bg-secondary">{{ $dk->trang_thai }}</span></td>
            <td>{{ $dk->ly_do_tu_choi ?? '—' }}</td>
            <td>
                @if ($dk->trang_thai === 'cho_duyet')
                    <form method="POST" action="{{ route('sinhvien.dangky.huy', $dk) }}" onsubmit="return confirm('Huỷ đăng ký này?')">
                        @csrf
                        <button class="btn btn-sm btn-outline-danger">Huỷ đăng ký</button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $dangKys->links() }}
@endsection
