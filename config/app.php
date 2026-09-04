<?php

use Illuminate\Support\Facades\Facade;

return [

    'name' => env('APP_NAME', 'Thi chứng chỉ HVNH'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'timezone' => env('APP_TIMEZONE', 'Asia/Ho_Chi_Minh'),

    'locale' => env('APP_LOCALE', 'vi'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'vi_VN'),

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    // Tên miền email trường dùng để kiểm tra khi Sinh viên đăng ký (App\Http\Controllers\Auth\RegisterController)
    'student_email_domain' => env('STUDENT_EMAIL_DOMAIN', '@hvnh.edu.vn'),

];
