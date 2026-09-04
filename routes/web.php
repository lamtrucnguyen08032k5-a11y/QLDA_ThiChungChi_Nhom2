<?php

use App\Http\Controllers\Admin\ChamThiController as AdminChamThiController;
use App\Http\Controllers\Admin\ChungNhanController as AdminChungNhanController;
use App\Http\Controllers\Admin\DangKyController as AdminDangKyController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DeThiController;
use App\Http\Controllers\Admin\KetQuaController as AdminKetQuaController;
use App\Http\Controllers\Admin\KhoaController;
use App\Http\Controllers\Admin\LichThiController;
use App\Http\Controllers\Admin\PhucKhaoController as AdminPhucKhaoController;
use App\Http\Controllers\Admin\SvWhitelistController;
use App\Http\Controllers\Admin\ToChucThiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GiangVien\ChamThiController as GvChamThiController;
use App\Http\Controllers\GiangVien\DashboardController as GvDashboardController;
use App\Http\Controllers\GiangVien\PhucKhaoController as GvPhucKhaoController;
use App\Http\Controllers\Khoa\DashboardController as KhoaDashboardController;
use App\Http\Controllers\Khoa\GiangVienController as KhoaGiangVienController;
use App\Http\Controllers\Khoa\TienDoChamController;
use App\Http\Controllers\SinhVien\ChungNhanController as SvChungNhanController;
use App\Http\Controllers\SinhVien\DangKyThiController;
use App\Http\Controllers\SinhVien\DashboardController as SvDashboardController;
use App\Http\Controllers\SinhVien\KetQuaController as SvKetQuaController;
use App\Http\Controllers\SinhVien\PhucKhaoController as SvPhucKhaoController;
use App\Http\Controllers\SinhVien\ThiController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dang-nhap');

/*
|--------------------------------------------------------------------------
| M1 - Xác thực & Tài khoản (khách)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [LoginController::class, 'showForm'])->name('login');
    Route::post('/dang-nhap', [LoginController::class, 'login']);

    Route::get('/dang-ky', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/dang-ky', [RegisterController::class, 'submit'])->name('register.submit');
    Route::get('/dang-ky/xac-minh/{token}', [RegisterController::class, 'showVerify'])->name('register.verify');
    Route::post('/dang-ky/hoan-tat', [RegisterController::class, 'completeRegistration'])->name('register.complete');

    Route::get('/quen-mat-khau', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
    Route::post('/quen-mat-khau', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/dat-lai-mat-khau/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/dat-lai-mat-khau', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/dang-xuat', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN (Phòng khảo thí)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // M1: Quản lý Khoa & tạo tài khoản Khoa/Giảng viên
    Route::resource('khoa', KhoaController::class)->except(['destroy']);
    Route::post('khoa/{khoa}/giang-vien', [KhoaController::class, 'storeGiangVien'])->name('khoa.giangvien.store');
    Route::put('khoa/{khoa}/giang-vien/{giangvien}', [KhoaController::class, 'updateGiangVien'])->name('khoa.giangvien.update');
    Route::delete('khoa/{khoa}/giang-vien/{giangvien}', [KhoaController::class, 'destroyGiangVien'])->name('khoa.giangvien.destroy');

    // Kho email sinh viên hợp lệ (whitelist) để SV có thể tự đăng ký
    Route::get('sinh-vien', [SvWhitelistController::class, 'index'])->name('svwhitelist.index');
    Route::post('sinh-vien', [SvWhitelistController::class, 'storeSingle'])->name('svwhitelist.store');
    Route::post('sinh-vien/import', [SvWhitelistController::class, 'import'])->name('svwhitelist.import');
    Route::delete('sinh-vien/{sv}', [SvWhitelistController::class, 'destroy'])->name('svwhitelist.destroy');

    // M2: Quản lý kỳ thi / lịch thi
    Route::resource('lich-thi', LichThiController::class)
        ->except(['show'])
        ->names('lichthi')
        ->parameters(['lich-thi' => 'lichthi']);

    // M3: Kho đề thi
    Route::resource('de-thi', DeThiController::class)
        ->except(['edit', 'update'])
        ->names('dethi')
        ->parameters(['de-thi' => 'dethi']);
    Route::post('de-thi/{dethi}/cau-hoi', [DeThiController::class, 'storeQuestion'])->name('dethi.cauhoi.store');
    Route::post('de-thi/{dethi}/import', [DeThiController::class, 'importQuestions'])->name('dethi.import');
    Route::delete('de-thi/{dethi}/cau-hoi/{cauhoi}', [DeThiController::class, 'destroyQuestion'])->name('dethi.cauhoi.destroy');

    // M4: Đăng ký thi - duyệt/từ chối
    Route::get('lich-thi/{lichthi}/dang-ky', [AdminDangKyController::class, 'index'])->name('dangky.index');
    Route::post('lich-thi/{lichthi}/dang-ky/{dangky}/duyet', [AdminDangKyController::class, 'approve'])->name('dangky.approve');
    Route::post('lich-thi/{lichthi}/dang-ky/{dangky}/tu-choi', [AdminDangKyController::class, 'reject'])->name('dangky.reject');

    // M5: Tổ chức thi
    Route::get('to-chuc-thi', [ToChucThiController::class, 'index'])->name('tochuc.index');
    Route::post('to-chuc-thi/{lichthi}/bat-dau', [ToChucThiController::class, 'batDau'])->name('tochuc.batdau');
    Route::post('to-chuc-thi/{lichthi}/ket-thuc', [ToChucThiController::class, 'ketThuc'])->name('tochuc.ketthuc');

    // M6: Theo dõi tiến độ chấm
    Route::get('cham-thi/tien-do', [AdminChamThiController::class, 'index'])->name('chamthi.tiendo');

    // M7: Kết quả
    Route::get('lich-thi/{lichthi}/ket-qua', [AdminKetQuaController::class, 'index'])->name('ketqua.index');
    Route::post('lich-thi/{lichthi}/ket-qua/cong-bo', [AdminKetQuaController::class, 'congBo'])->name('ketqua.congbo');

    // M8: Phúc khảo
    Route::get('phuc-khao', [AdminPhucKhaoController::class, 'index'])->name('phuckhao.index');
    Route::get('phuc-khao/{phuckhao}', [AdminPhucKhaoController::class, 'show'])->name('phuckhao.show');

    // M9: Chứng nhận
    Route::get('chung-nhan', [AdminChungNhanController::class, 'index'])->name('chungnhan.index');
    Route::post('chung-nhan/{chungnhan}/cap', [AdminChungNhanController::class, 'capNhan'])->name('chungnhan.cap');
    Route::post('chung-nhan/{chungnhan}/tu-choi', [AdminChungNhanController::class, 'tuChoi'])->name('chungnhan.tuchoi');
});

/*
|--------------------------------------------------------------------------
| KHOA (tài khoản Khoa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:khoa'])->prefix('khoa')->name('khoa.')->group(function () {
    Route::get('/', [KhoaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/giang-vien', [KhoaGiangVienController::class, 'index'])->name('giangvien.index');
    Route::post('/giang-vien', [KhoaGiangVienController::class, 'store'])->name('giangvien.store');
    Route::get('/tien-do-cham', [TienDoChamController::class, 'index'])->name('tiendocham');
});

/*
|--------------------------------------------------------------------------
| GIANG VIEN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:giangvien'])->prefix('giang-vien')->name('giangvien.')->group(function () {
    Route::get('/', [GvDashboardController::class, 'index'])->name('dashboard');

    Route::get('/cham-thi', [GvChamThiController::class, 'index'])->name('cham-thi.index');
    Route::get('/cham-thi/{baithi}', [GvChamThiController::class, 'show'])->name('cham-thi.show');
    Route::post('/cham-thi/{baithi}', [GvChamThiController::class, 'luuDiem'])->name('cham-thi.luu');

    Route::get('/phuc-khao', [GvPhucKhaoController::class, 'index'])->name('phuc-khao.index');
    Route::get('/phuc-khao/{phuckhao}', [GvPhucKhaoController::class, 'show'])->name('phuc-khao.show');
    Route::post('/phuc-khao/{phuckhao}', [GvPhucKhaoController::class, 'xuLy'])->name('phuc-khao.xuly');
});

/*
|--------------------------------------------------------------------------
| SINH VIEN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:sinhvien'])->prefix('sinh-vien')->name('sinhvien.')->group(function () {
    Route::get('/', [SvDashboardController::class, 'index'])->name('dashboard');

    // M4
    Route::get('/dang-ky-thi', [DangKyThiController::class, 'index'])->name('dangky.index');
    Route::post('/dang-ky-thi/{lichthi}', [DangKyThiController::class, 'store'])->name('dangky.store');
    Route::get('/dang-ky-cua-toi', [DangKyThiController::class, 'cuaToi'])->name('dangky.cua-toi');
    Route::post('/dang-ky/{dangky}/huy', [DangKyThiController::class, 'huy'])->name('dangky.huy');

    // M5
    Route::get('/thi', [ThiController::class, 'index'])->name('thi.index');
    Route::get('/thi/{dangky}', [ThiController::class, 'chiTiet'])->name('thi.chi-tiet');
    Route::post('/thi/{dangky}/nhap-ma', [ThiController::class, 'nhapMa'])->name('thi.nhap-ma');
    Route::get('/thi/lam-bai/{baithi}', [ThiController::class, 'lamBai'])->name('thi.lam-bai');
    Route::post('/thi/nop-bai/{baithi}', [ThiController::class, 'nopBai'])->name('thi.nop-bai');

    // M7
    Route::get('/ket-qua', [SvKetQuaController::class, 'index'])->name('ketqua.index');
    Route::get('/ket-qua/{baithi}', [SvKetQuaController::class, 'show'])->name('ketqua.show');

    // M8
    Route::get('/phuc-khao', [SvPhucKhaoController::class, 'index'])->name('phuc-khao.index');
    Route::get('/phuc-khao/{baithi}/tao', [SvPhucKhaoController::class, 'create'])->name('phuc-khao.create');
    Route::post('/phuc-khao/{baithi}', [SvPhucKhaoController::class, 'store'])->name('phuc-khao.store');

    // M9
    Route::get('/chung-nhan', [SvChungNhanController::class, 'index'])->name('chung-nhan.index');
    Route::get('/chung-nhan/{baithi}/tao', [SvChungNhanController::class, 'create'])->name('chung-nhan.create');
    Route::post('/chung-nhan/{baithi}', [SvChungNhanController::class, 'store'])->name('chung-nhan.store');
});
