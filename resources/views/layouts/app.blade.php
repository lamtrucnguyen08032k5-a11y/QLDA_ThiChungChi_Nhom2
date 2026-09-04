<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hệ thống thi chứng chỉ HVNH')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; }
        .sidebar { min-height: 100vh; background: #0d2b4e; }
        .sidebar a { color: #cfe0f5; text-decoration: none; display: block; padding: .6rem 1rem; border-radius: .375rem; }
        .sidebar a.active, .sidebar a:hover { background: #133b6b; color: #fff; }
        .sidebar .brand { color: #fff; font-weight: 700; padding: 1rem; border-bottom: 1px solid rgba(255,255,255,.1); margin-bottom: .5rem;}
    </style>
</head>
<body>
<div class="d-flex">
    <nav class="sidebar" style="width:250px;">
        <div class="brand">HVNH Khảo thí</div>
        <div class="px-2">
            @php $u = auth()->user(); @endphp
            @if ($u->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Tổng quan</a>
                <a href="{{ route('admin.khoa.index') }}" class="{{ request()->routeIs('admin.khoa.*') ? 'active' : '' }}">Quản lý Khoa</a>
                <a href="{{ route('admin.svwhitelist.index') }}" class="{{ request()->routeIs('admin.svwhitelist.*') ? 'active' : '' }}">Kho email Sinh viên</a>
                <a href="{{ route('admin.lichthi.index') }}" class="{{ request()->routeIs('admin.lichthi.*') ? 'active' : '' }}">Lịch thi</a>
                <a href="{{ route('admin.dethi.index') }}" class="{{ request()->routeIs('admin.dethi.*') ? 'active' : '' }}">Kho đề thi</a>
                <a href="{{ route('admin.tochuc.index') }}" class="{{ request()->routeIs('admin.tochuc.*') ? 'active' : '' }}">Tổ chức thi</a>
                <a href="{{ route('admin.chamthi.tiendo') }}" class="{{ request()->routeIs('admin.chamthi.*') ? 'active' : '' }}">Tiến độ chấm</a>
                <a href="{{ route('admin.phuckhao.index') }}" class="{{ request()->routeIs('admin.phuckhao.*') ? 'active' : '' }}">Phúc khảo</a>
                <a href="{{ route('admin.chungnhan.index') }}" class="{{ request()->routeIs('admin.chungnhan.*') ? 'active' : '' }}">Chứng nhận</a>
            @elseif ($u->role === 'khoa')
                <a href="{{ route('khoa.dashboard') }}" class="{{ request()->routeIs('khoa.dashboard') ? 'active' : '' }}">Tổng quan</a>
                <a href="{{ route('khoa.giangvien.index') }}" class="{{ request()->routeIs('khoa.giangvien.*') ? 'active' : '' }}">Giảng viên</a>
                <a href="{{ route('khoa.tiendocham') }}" class="{{ request()->routeIs('khoa.tiendocham') ? 'active' : '' }}">Tiến độ chấm</a>
            @elseif ($u->role === 'giangvien')
                <a href="{{ route('giangvien.dashboard') }}" class="{{ request()->routeIs('giangvien.dashboard') ? 'active' : '' }}">Tổng quan</a>
                <a href="{{ route('giangvien.cham-thi.index') }}" class="{{ request()->routeIs('giangvien.cham-thi.*') ? 'active' : '' }}">Chấm bài thi</a>
                <a href="{{ route('giangvien.phuc-khao.index') }}" class="{{ request()->routeIs('giangvien.phuc-khao.*') ? 'active' : '' }}">Xử lý phúc khảo</a>
            @else
                <a href="{{ route('sinhvien.dashboard') }}" class="{{ request()->routeIs('sinhvien.dashboard') ? 'active' : '' }}">Tổng quan</a>
                <a href="{{ route('sinhvien.dangky.index') }}" class="{{ request()->routeIs('sinhvien.dangky.*') ? 'active' : '' }}">Đăng ký thi</a>
                <a href="{{ route('sinhvien.thi.index') }}" class="{{ request()->routeIs('sinhvien.thi.*') ? 'active' : '' }}">Thi</a>
                <a href="{{ route('sinhvien.ketqua.index') }}" class="{{ request()->routeIs('sinhvien.ketqua.*') ? 'active' : '' }}">Kết quả</a>
                <a href="{{ route('sinhvien.phuc-khao.index') }}" class="{{ request()->routeIs('sinhvien.phuc-khao.*') ? 'active' : '' }}">Phúc khảo</a>
                <a href="{{ route('sinhvien.chung-nhan.index') }}" class="{{ request()->routeIs('sinhvien.chung-nhan.*') ? 'active' : '' }}">Chứng nhận</a>
            @endif
        </div>
    </nav>
    <main class="flex-grow-1">
        <nav class="navbar navbar-light bg-white border-bottom px-4">
            <span class="fw-semibold">@yield('title', 'Trang chủ')</span>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">Đăng xuất</button>
                </form>
            </div>
        </nav>
        <div class="p-4">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
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
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
