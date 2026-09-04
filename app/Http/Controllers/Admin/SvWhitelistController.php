<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SvWhitelist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// "Kho mail HVNH" - Admin import danh sách sinh viên hợp lệ được phép đăng ký tài khoản.
// Import bằng file Excel (.xlsx) theo mẫu: ma_sv | ho_ten | email | lop | khoa_hoc
class SvWhitelistController extends Controller
{
    public function index(Request $request)
    {
        $q = SvWhitelist::query();
        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($qq) use ($s) {
                $qq->where('ma_sv', 'like', "%{$s}%")
                    ->orWhere('ho_ten', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%");
            });
        }
        $sinhViens = $q->orderBy('ma_sv')->paginate(20)->withQueryString();
        return view('admin.giangvien.sv-whitelist', compact('sinhViens'));
    }

    public function storeSingle(Request $request)
    {
        $data = $request->validate([
            'ma_sv' => 'required|string|unique:sv_whitelists,ma_sv',
            'ho_ten' => 'required|string|max:255',
            'email' => 'required|email|unique:sv_whitelists,email',
            'lop' => 'nullable|string',
            'khoa_hoc' => 'nullable|string',
        ]);
        SvWhitelist::create($data);
        return back()->with('status', 'Đã thêm sinh viên vào danh sách.');
    }

    // Import hàng loạt qua file CSV/Excel đơn giản (mỗi dòng: ma_sv,ho_ten,email,lop,khoa_hoc)
    // Với file .xlsx thật, khuyến nghị cài package maatwebsite/excel (xem README) để đọc chính xác hơn.
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt,xlsx']);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);
        $count = 0;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 3 || empty($row[0])) {
                    continue;
                }
                SvWhitelist::updateOrCreate(
                    ['ma_sv' => trim($row[0])],
                    [
                        'ho_ten' => trim($row[1] ?? ''),
                        'email' => strtolower(trim($row[2] ?? '')),
                        'lop' => trim($row[3] ?? null),
                        'khoa_hoc' => trim($row[4] ?? null),
                    ]
                );
                $count++;
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($handle);
            return back()->withErrors(['file' => 'Lỗi khi import: ' . $e->getMessage()]);
        }
        fclose($handle);

        return back()->with('status', "Import thành công {$count} sinh viên.");
    }

    public function destroy(SvWhitelist $sv)
    {
        $sv->delete();
        return back()->with('status', 'Đã xoá khỏi danh sách.');
    }
}
