<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CauHoi;
use App\Models\DeThi;
use App\Models\Khoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// M3 - Kho đề thi (UC3.1 Nhập & xử lý đề thi, UC3.2 Quản lý kho đề)
class DeThiController extends Controller
{
    public function index(Request $request)
    {
        $q = DeThi::with('khoa')->withCount('cauHois');
        if ($request->filled('khoa_id')) {
            $q->where('khoa_id', $request->khoa_id);
        }
        $deThis = $q->orderByDesc('id')->paginate(15)->withQueryString();
        $khoas = Khoa::orderBy('ten_khoa')->get();
        return view('admin.dethi.index', compact('deThis', 'khoas'));
    }

    public function create()
    {
        $khoas = Khoa::where('active', true)->orderBy('ten_khoa')->get();
        return view('admin.dethi.create', compact('khoas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ma_de' => 'required|string|max:50|unique:de_this,ma_de',
            'ten_de' => 'required|string|max:255',
            'loai_chung_chi' => 'required|in:cntt,tienganh',
            'khoa_id' => 'required|exists:khoas,id',
            'file' => 'nullable|file|mimes:xlsx,csv,txt|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('de-thi', 'local');
        }

        $deThi = DeThi::create([
            'ma_de' => $data['ma_de'],
            'ten_de' => $data['ten_de'],
            'loai_chung_chi' => $data['loai_chung_chi'],
            'khoa_id' => $data['khoa_id'],
            'file_goc' => $filePath,
        ]);

        // Nếu có file, tự động import câu hỏi theo mẫu CSV:
        // noi_dung,loai_cau(tracnghiem/tuluan),dap_an_a,dap_an_b,dap_an_c,dap_an_d,dap_an_dung,diem
        if ($filePath && in_array($request->file('file')->getClientOriginalExtension(), ['csv', 'txt'])) {
            $this->importCauHoiFromCsv(Storage::path($filePath), $deThi);
        }

        return redirect()->route('admin.dethi.show', $deThi)->with('status', 'Tạo đề thi thành công.');
    }

    public function show(DeThi $dethi)
    {
        $cauHois = $dethi->cauHois()->get();
        return view('admin.dethi.show', compact('dethi', 'cauHois'));
    }

    public function importQuestions(Request $request, DeThi $dethi)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt,xlsx|max:10240']);
        $path = $request->file('file')->getRealPath();
        $count = $this->importCauHoiFromCsv($path, $dethi);
        return back()->with('status', "Đã nhập {$count} câu hỏi vào đề thi từ file.");
    }

    private function importCauHoiFromCsv(string $path, DeThi $dethi): int
    {
        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);
        $count = 0;
        $thuTu = $dethi->cauHois()->max('thu_tu') ?? 0;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                if (empty($row[0])) {
                    continue;
                }
                $thuTu++;
                CauHoi::create([
                    'de_thi_id' => $dethi->id,
                    'noi_dung' => trim($row[0]),
                    'loai_cau' => trim($row[1] ?? 'tracnghiem') === 'tuluan' ? 'tuluan' : 'tracnghiem',
                    'dap_an_a' => $row[2] ?? null,
                    'dap_an_b' => $row[3] ?? null,
                    'dap_an_c' => $row[4] ?? null,
                    'dap_an_d' => $row[5] ?? null,
                    'dap_an_dung' => $row[6] ?? null,
                    'diem' => is_numeric($row[7] ?? null) ? $row[7] : 1,
                    'thu_tu' => $thuTu,
                ]);
                $count++;
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
        }
        fclose($handle);
        return $count;
    }

    public function storeQuestion(Request $request, DeThi $dethi)
    {
        $data = $request->validate([
            'noi_dung' => 'required|string',
            'loai_cau' => 'required|in:tracnghiem,tuluan',
            'dap_an_a' => 'nullable|string',
            'dap_an_b' => 'nullable|string',
            'dap_an_c' => 'nullable|string',
            'dap_an_d' => 'nullable|string',
            'dap_an_dung' => 'nullable|in:A,B,C,D',
            'diem' => 'required|numeric|min:0.1',
        ]);
        $data['de_thi_id'] = $dethi->id;
        $data['thu_tu'] = ($dethi->cauHois()->max('thu_tu') ?? 0) + 1;
        CauHoi::create($data);
        return back()->with('status', 'Thêm câu hỏi thành công.');
    }

    public function destroyQuestion(DeThi $dethi, CauHoi $cauhoi)
    {
        $cauhoi->delete();
        return back()->with('status', 'Đã xoá câu hỏi.');
    }

    public function destroy(DeThi $dethi)
    {
        $dethi->delete();
        return redirect()->route('admin.dethi.index')->with('status', 'Đã xoá đề thi.');
    }
}
