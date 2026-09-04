@extends('layouts.app')
@section('title', 'Tổng quan')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary"><div class="card-body">
            <div class="small">Tổng số lịch thi</div>
            <div class="fs-3 fw-bold">{{ $tongLichThi }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success"><div class="card-body">
            <div class="small">Đăng ký đã duyệt</div>
            <div class="fs-3 fw-bold">{{ $tongDangKyDaDuyet }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-info"><div class="card-body">
            <div class="small">Số thí sinh đã thi</div>
            <div class="fs-3 fw-bold">{{ $tongDaThi }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning"><div class="card-body">
            <div class="small">Tổng doanh thu lệ phí</div>
            <div class="fs-4 fw-bold">{{ number_format($tongDoanhThu) }} đ</div>
        </div></div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card"><div class="card-header">Doanh thu theo tháng</div>
        <div class="card-body"><canvas id="chartDoanhThu" height="200"></canvas></div></div>
    </div>
    <div class="col-md-6">
        <div class="card"><div class="card-header">Số sinh viên theo khoá học</div>
        <div class="card-body"><canvas id="chartKhoaHoc" height="200"></canvas></div></div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">Số thí sinh theo ca thi gần đây</div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead><tr><th>Tên kỳ thi</th><th>Ngày thi</th><th>Khoa</th><th>Số thí sinh</th></tr></thead>
            <tbody>
            @foreach ($thiSinhTheoCa as $ca)
                <tr><td>{{ $ca->ten_ky_thi }}</td><td>{{ \Carbon\Carbon::parse($ca->ngay_thi)->format('d/m/Y') }}</td><td>{{ $ca->khoa->ten_khoa }}</td><td>{{ $ca->so_thi_sinh }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('chartDoanhThu'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($doanhThuTheoThang->pluck('thang')) !!},
        datasets: [{ label: 'Doanh thu (đ)', data: {!! json_encode($doanhThuTheoThang->pluck('tong')) !!}, backgroundColor: '#0d6efd' }]
    }
});
new Chart(document.getElementById('chartKhoaHoc'), {
    type: 'pie',
    data: {
        labels: {!! json_encode($sinhVienTheoKhoaHoc->pluck('khoa_hoc')) !!},
        datasets: [{ data: {!! json_encode($sinhVienTheoKhoaHoc->pluck('so_luong')) !!}, backgroundColor: ['#0d6efd','#198754','#ffc107','#dc3545','#6f42c1','#20c997'] }]
    }
});
</script>
@endsection
