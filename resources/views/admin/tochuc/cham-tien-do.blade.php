@extends('layouts.app')
@section('title', 'Tiến độ chấm bài')
@section('content')
<table class="table table-bordered bg-white">
    <thead><tr><th>Kỳ thi</th><th>Khoa</th><th>Ngày thi</th><th>Bài đã nộp</th><th>Bài đã chấm</th><th>Tiến độ</th></tr></thead>
    <tbody>
    @foreach ($lichThis as $lt)
        @php $pct = $lt->tong_bai_da_nop > 0 ? round($lt->tong_bai_da_cham / $lt->tong_bai_da_nop * 100) : 0; @endphp
        <tr>
            <td>{{ $lt->ten_ky_thi }}</td>
            <td>{{ $lt->khoa->ten_khoa }}</td>
            <td>{{ $lt->ngay_thi->format('d/m/Y') }}</td>
            <td>{{ $lt->tong_bai_da_nop }}</td>
            <td>{{ $lt->tong_bai_da_cham }}</td>
            <td style="min-width:180px;">
                <div class="progress"><div class="progress-bar" style="width: {{ $pct }}%">{{ $pct }}%</div></div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $lichThis->links() }}
@endsection
