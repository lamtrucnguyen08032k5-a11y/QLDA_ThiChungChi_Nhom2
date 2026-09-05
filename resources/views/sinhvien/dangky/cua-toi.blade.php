@extends('layouts.sinhvien')
@section('title', 'Đăng ký của tôi')
@section('content')
<div class="card">
    <table class="table mb-0 align-middle">
        <thead class="table-light"><tr><th>Mã đăng ký</th><th>Kỳ thi</th><th>Ngày thi</th><th>Trạng thái hồ sơ</th><th>Thanh toán</th><th>Lý do từ chối</th><th></th></tr></thead>
        <tbody>
        @forelse ($dangKys as $dk)
            <tr>
                <td>{{ $dk->ma_dang_ky }}</td>
                <td>{{ $dk->lichThi->ten_ky_thi }}</td>
                <td>{{ $dk->lichThi->ngay_thi->format('d/m/Y') }}</td>
                <td><span class="badge badge-{{ str_replace('_','-', $dk->trang_thai) }}">{{ $dk->nhanTrangThaiLabel() }}</span></td>
                <td><span class="badge badge-{{ str_replace('_','-', $dk->trang_thai_thanh_toan) }}">{{ $dk->nhanTrangThaiThanhToanLabel() }}</span></td>
                <td>{{ $dk->ly_do_tu_choi ?? '—' }}</td>
                <td class="d-flex gap-1">
                    @if ($dk->trang_thai_thanh_toan !== 'da_thanh_toan')
                        <a href="{{ route('sinhvien.dangky.buoc3', $dk) }}" class="btn btn-sm btn-primary">Thanh toán</a>
                    @else
                        <a href="{{ route('sinhvien.dangky.buoc4', $dk) }}" class="btn btn-sm btn-outline-primary">Xem</a>
                    @endif
                    @if (in_array($dk->trang_thai, ['cho_duyet','cho_bo_sung']))
                        <form method="POST" action="{{ route('sinhvien.dangky.huy', $dk) }}" onsubmit="return confirm('Huỷ đăng ký này?')">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">Huỷ</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Bạn chưa đăng ký kỳ thi nào.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $dangKys->links() }}</div>
@endsection
