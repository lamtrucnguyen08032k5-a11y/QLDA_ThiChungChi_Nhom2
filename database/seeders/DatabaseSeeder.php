<?php

namespace Database\Seeders;

use App\Models\Khoa;
use App\Models\SvWhitelist;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tài khoản Admin (Phòng khảo thí) mặc định
        User::updateOrCreate(
            ['email' => 'admin@hvnh.edu.vn'],
            [
                'role' => 'admin',
                'ma_so' => 'ADMIN001',
                'name' => 'Quản trị hệ thống - Phòng khảo thí',
                'password' => Hash::make('Admin@123'),
                'email_verified_at' => now(),
            ]
        );

        // Dữ liệu mẫu: 2 Khoa + tài khoản Khoa tương ứng
        $khoaCNTT = Khoa::updateOrCreate(
            ['ma_khoa' => 'CNTT'],
            ['ten_khoa' => 'Khoa Công nghệ thông tin', 'email' => 'khoa.cntt@hvnh.edu.vn', 'active' => true]
        );

        $khoaNN = Khoa::updateOrCreate(
            ['ma_khoa' => 'NN'],
            ['ten_khoa' => 'Khoa Ngoại ngữ', 'email' => 'khoa.nn@hvnh.edu.vn', 'active' => true]
        );

        foreach ([$khoaCNTT, $khoaNN] as $khoa) {
            User::updateOrCreate(
                ['email' => $khoa->email],
                [
                    'role' => 'khoa',
                    'ma_so' => $khoa->ma_khoa,
                    'name' => 'Tài khoản ' . $khoa->ten_khoa,
                    'password' => Hash::make('Khoa@123'),
                    'khoa_id' => $khoa->id,
                    'email_verified_at' => now(),
                ]
            );
        }

        // Giảng viên mẫu
        User::updateOrCreate(
            ['email' => 'giangvien.cntt@hvnh.edu.vn'],
            [
                'role' => 'giangvien',
                'ma_so' => 'GV001',
                'name' => 'Nguyễn Văn A',
                'password' => Hash::make('GiangVien@123'),
                'khoa_id' => $khoaCNTT->id,
                'email_verified_at' => now(),
            ]
        );

        // Kho email sinh viên (whitelist) mẫu để test đăng ký tài khoản
        SvWhitelist::updateOrCreate(
            ['ma_sv' => '22A4000001'],
            [
                'ho_ten' => 'Trần Thị B',
                'email' => 'sv22a4000001@hvnh.edu.vn',
                'lop' => 'K22CLC1',
                'khoa_hoc' => 'K22',
            ]
        );
    }
}
