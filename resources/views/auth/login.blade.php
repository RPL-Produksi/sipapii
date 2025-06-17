@extends('layouts.auth')
@section('title', 'Login')

@push('css')
    {{-- CSS Only For This Page --}}
@endpush

@section('content-left')
    <div class="auth-logo">
        <h3 class="text-primary">SIPAPII</h3>
    </div>
    <h2 class="auth-title" style="font-size: 32pt">Log in.</h2>
    <p class="auth-subtitle mb-5" style="font-size: 14pt">Silahkan login menggunakan username dan password yang telah
        diberikan</p>

    <form action="{{ route('post.login') }}" method="POST">
        @csrf
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="text" class="form-control form-control-md" name="username" placeholder="Username" required>
            <div class="form-control-icon">
                <i class="bi bi-person"></i>
            </div>
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="password" class="form-control form-control-md" name="password" placeholder="Password" required>
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
        </div>
        @if (Session::has('error'))
            <div class="alert alert-danger fade show text-center" role="alert">
                <strong>{{ Session::get('error') }}</strong>
            </div>
        @endif
        <div class="form-check form-check-lg d-flex align-items-end">
            <input class="form-check-input me-2" type="checkbox" value="" name="remember" id="checkboxRemember">
            <label class="form-check-label text-gray-600" for="checkboxRemember">
                Keep me logged in
            </label>
        </div>

        <button class="btn btn-primary btn-block btn-md shadow-lg mt-5">Log in</button>
    </form>
@endsection

@section('content-right')

@endsection

@push('js')
    {{-- JS Only For This Page --}}
@endpush
