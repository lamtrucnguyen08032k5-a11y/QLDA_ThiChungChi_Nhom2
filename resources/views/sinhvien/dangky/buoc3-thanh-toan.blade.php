@extends('layouts.sinhvien')
@section('title', 'Thanh toán lệ phí thi')
@section('content')

<ul class="hvnh-steps">
    <li class="done">Bước 1: Cập nhật thông tin cá nhân</li>
    <li class="done">Bước 2: Chọn thông tin đăng ký thi</li>
    <li class="active">Bước 3: Xác nhận và thanh toán</li>
    <li>Bước 4: Trạng thái thanh toán</li>
</ul>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card mb-3">
            <div class="card-header-blue">Thông tin thanh toán</div>
            <table class="table table-kv mb-0">
                <tr><th>Mã đăng ký</th><td class="fw-bold">{{ $dangky->ma_dang_ky }}</td></tr>
                <tr><th>Bài thi</th><td>{{ $dangky->lichThi->ten_ky_thi }}</td></tr>
                <tr><th>Ngày thi</th><td>{{ $dangky->lichThi->ngay_thi->format('d/m/Y') }}</td></tr>
                <tr><th>Số tiền phải thanh toán</th><td class="fw-bold fs-5" style="color:var(--hvnh-orange)">{{ number_format($dangky->so_tien) }}đ</td></tr>
            </table>
        </div>

        <form method="POST" action="{{ route('sinhvien.dangky.buoc3.khoitao', $dangky) }}">
            @csrf
            <div class="card mb-3">
                <div class="card-header-blue">Chọn phương thức thanh toán</div>
                <div class="card-body">
                    <div class="form-check border rounded p-3 mb-3">
                        <input class="form-check-input" type="radio" name="phuong_thuc_thanh_toan" id="pt_vnpay" value="vnpay" checked>
                        <label class="form-check-label w-100" for="pt_vnpay">
                            <strong>VNPAY</strong> — Ví điện tử VNPAY-QR, thẻ ATM, thẻ quốc tế Visa/Master/JCB
                        </label>
                    </div>
                    <div class="form-check border rounded p-3">
                        <input class="form-check-input" type="radio" name="phuong_thuc_thanh_toan" id="pt_napas" value="napas">
                        <label class="form-check-label w-100" for="pt_napas">
                            <strong>NAPAS</strong> — Thẻ ATM nội địa qua liên minh thẻ Napas (thanh toán qua cổng VNPAY)
                        </label>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mb-5">
                <button class="btn btn-primary px-4">Tiến hành thanh toán →</button>
            </div>
        </form>
    </div>
</div>
@endsection
