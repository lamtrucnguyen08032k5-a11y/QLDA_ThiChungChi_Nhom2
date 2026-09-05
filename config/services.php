<?php

return [

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // Cổng thanh toán VNPAY. Dùng chung cho 2 phương thức "vnpay" (VNPAY-QR/ví/thẻ quốc tế)
    // và "napas" (thẻ ATM nội địa qua liên minh thẻ Napas - vnp_BankCode=VNBANK).
    // Đăng ký tài khoản merchant sandbox tại https://sandbox.vnpayment.vn để lấy vnp_TmnCode/vnp_HashSecret.
    'vnpay' => [
        'tmn_code' => env('VNPAY_TMN_CODE'),
        'hash_secret' => env('VNPAY_HASH_SECRET'),
        'url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
        'return_url' => env('VNPAY_RETURN_URL', env('APP_URL').'/thanh-toan/vnpay/return'),
    ],

];
