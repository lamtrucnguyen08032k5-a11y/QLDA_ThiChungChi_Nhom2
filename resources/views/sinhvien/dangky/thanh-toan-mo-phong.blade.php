@extends('layouts.sinhvien')
@section('title', 'Cổng thanh toán')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="alert alert-warning">
            Chưa cấu hình tài khoản merchant VNPAY thật (<code>VNPAY_TMN_CODE</code> / <code>VNPAY_HASH_SECRET</code> trong <code>.env</code>).
            Đây là cổng thanh toán <strong>mô phỏng</strong> để demo trọn vẹn luồng thanh toán {{ strtoupper($dangky->phuong_thuc_thanh_toan) }}.
        </div>
        <div class="card">
            <div class="card-header-blue">Xác nhận thanh toán ({{ strtoupper($dangky->phuong_thuc_thanh_toan) }})</div>
            <table class="table table-kv mb-0">
                <tr><th>Mã giao dịch</th><td>{{ $dangky->ma_giao_dich }}</td></tr>
                <tr><th>Số tiền</th><td class="fw-bold">{{ number_format($dangky->so_tien) }}đ</td></tr>
            </table>
            <div class="card-body d-flex gap-2">
                <form method="POST" action="{{ route('sinhvien.dangky.thanhtoan.mophong.xuly', $dangky) }}" class="flex-grow-1">
                    @csrf
                    <input type="hidden" name="ket_qua" value="thanh_cong">
                    <button class="btn btn-primary w-100">Thanh toán thành công</button>
                </form>
                <form method="POST" action="{{ route('sinhvien.dangky.thanhtoan.mophong.xuly', $dangky) }}" class="flex-grow-1">
                    @csrf
                    <input type="hidden" name="ket_qua" value="that_bai">
                    <button class="btn btn-outline-danger w-100">Mô phỏng thất bại</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
