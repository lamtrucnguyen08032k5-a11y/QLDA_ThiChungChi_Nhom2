<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hệ thống thi chứng chỉ HVNH')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-primary">HỌC VIỆN NGÂN HÀNG</h4>
                    <p class="text-muted">Hệ thống đăng ký thi chứng chỉ CNTT &amp; Tiếng Anh đầu ra</p>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body p-4">
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
                </div>
            </div>
        </div>
    </div>
</body>
</html>
