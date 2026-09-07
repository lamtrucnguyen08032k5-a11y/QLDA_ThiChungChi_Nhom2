<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hệ thống thi chứng chỉ HVNH')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
</head>
<body>
    <div class="hvnh-topbar">
        <div class="container d-flex justify-content-between flex-wrap">
            <span>TRUNG TÂM TIN HỌC NGOẠI NGỮ - HỌC VIỆN NGÂN HÀNG</span>
            <span class="d-flex gap-3">
                <a href="mailto:trungtamtinhocngoaingu@hvnh.edu.vn">✉ trungtamtinhocngoaingu@hvnh.edu.vn</a>
                <a href="tel:02435726385">☎ 024 3572 6385</a>
            </span>
        </div>
    </div>
    <nav class="hvnh-navbar py-2">
        <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
            <a href="{{ route('sinhvien.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <img src="{{ asset('images/logo.svg') }}" alt="Logo HVNH" width="42" height="46">
                <span>
                    <span class="brand-text d-block">HỌC VIỆN NGÂN HÀNG</span>
                    <span class="brand-sub">HỆ THỐNG ĐĂNG KÝ THI CHỨNG CHỈ</span>
                </span>
            </a>
            <div class="d-flex align-items-center gap-4 flex-wrap">
                <a class="nav-link {{ request()->routeIs('sinhvien.dashboard') ? 'active' : '' }}" href="{{ route('sinhvien.dashboard') }}">Trang chủ</a>
                <a class="nav-link {{ request()->routeIs('sinhvien.dangky.*') ? 'active' : '' }}" href="{{ route('sinhvien.dangky.index') }}">Đăng ký thi</a>
                <a class="nav-link {{ request()->routeIs('sinhvien.dangky.cua-toi') ? 'active' : '' }}" href="{{ route('sinhvien.dangky.cua-toi') }}">Đăng ký của tôi</a>
                <a class="nav-link {{ request()->routeIs('sinhvien.thi.*') ? 'active' : '' }}" href="{{ route('sinhvien.thi.index') }}">Thi</a>
                <a class="nav-link {{ request()->routeIs('sinhvien.ketqua.*') ? 'active' : '' }}" href="{{ route('sinhvien.ketqua.index') }}">Kết quả</a>
                <a class="nav-link {{ request()->routeIs('sinhvien.phuc-khao.*') ? 'active' : '' }}" href="{{ route('sinhvien.phuc-khao.index') }}">Phúc khảo</a>
                <a class="nav-link {{ request()->routeIs('sinhvien.chung-nhan.*') ? 'active' : '' }}" href="{{ route('sinhvien.chung-nhan.index') }}">Chứng nhận</a>
                <div class="dropdown">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text small text-muted">{{ auth()->user()->email }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item">Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="hvnh-page-title">
        <div class="container">
            <h1>@yield('title', 'Trang chủ')</h1>
        </div>
    </div>

    <div class="container pb-5">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if ($errors->any() && empty($hideErrorSummary))
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>

    <footer class="hvnh-footer">
        <div class="container d-flex justify-content-between flex-wrap gap-2">
            <span>© {{ date('Y') }} Học viện Ngân hàng — Hệ thống đăng ký thi chứng chỉ.</span>
            <span>Hotline: 038 980 7777</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
