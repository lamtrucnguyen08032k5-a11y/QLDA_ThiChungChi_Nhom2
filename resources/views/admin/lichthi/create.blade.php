@extends('layouts.app')
@section('title', 'Tạo lịch thi')
@section('content')
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('admin.lichthi.store') }}">
    @csrf
    @include('admin.lichthi._form', ['lichthi' => null])
    <button class="btn btn-primary mt-3">Tạo lịch thi</button>
    <a href="{{ route('admin.lichthi.index') }}" class="btn btn-outline-secondary mt-3">Hủy</a>
</form>
</div></div>
@endsection
