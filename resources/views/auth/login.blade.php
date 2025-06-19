@extends('layouts.auth')
@section('title', 'Login')

@push('css')
    {{-- CSS Only For This Page --}}
    <style>
        .auth-right {
            background: linear-gradient(to right, #2c3e50, #3498db);
            /* atau sesuai tema kamu */
            color: white;
            padding: 40px;
        }
    </style>
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
    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-white px-4">
        <img src="{{ asset('assets/static/images/auth-illustrations.png') }}" alt="Ilustrasi Login"
            style="max-width: 80%; margin-bottom: 20px;">

        <h4 class="text-white">Selamat Datang di SIPAPII</h4>
        <p class="text-white text-center mt-2">
            Sistem Informasi Praktik Anak Pada Instansi dan Industri <br>
            Membantu memonitor kegiatan PKL dengan efisien dan akurat.
        </p>

        <div class="mt-4 small text-white-50 text-center">
            <h4 id="quoteText" class="text-white" style="font-style: italic;">Sedang mengambil kutipan...</h4>
            {{-- <p>Butuh bantuan? Hubungi Admin:</p>
            <p>
                <i class="bi bi-envelope me-1"></i> admin@smkn2smi.sch.id <br>
                <i class="bi bi-phone me-1"></i> (0266) 123456
            </p> --}}
        </div>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
    <script>
        fetch('https://dummyjson.com/quotes/random')
            .then(response => response.json())
            .then(data => {
                document.getElementById('quoteText').innerText = `“${data.quote}”`;
            })
            .catch(err => {
                document.getElementById('quoteText').innerText = '“Gagal mengambil quote. Silakan coba lagi nanti.”';
                console.error(err);
            });
    </script>
@endpush
