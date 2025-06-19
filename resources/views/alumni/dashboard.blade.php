@extends('layouts.app-4')
@section('title', 'Sedang Dikembangkan')

@push('css')
    {{-- CSS Only For This Page --}}
@endpush

@section('content')
    <div class="text-center p-4">
        <img src="{{ asset('assets/static/images/undraw_maintenance_rjtm.svg') }}" alt="Under Development"
            style="max-width: 300px;">
        <h1 class="mt-4">Halaman Sedang Dikembangkan</h1>
        <p class="text-muted">Fitur ini belum tersedia saat ini. Silakan kembali nanti.</p>
        <a href="{{ route('logout') }}" class="btn btn-primary mt-3">
            <i class="fa-regular fa-left-from-bracket me-1"></i> Logout
        </a>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
@endpush
