<nav class="w-full bg-primary py-1 border-none px-4">
    <div class="container-fluid d-flex align-items-center justify-content-between text-white my-3">
        <div class="d-flex align-items-center justify-content-center">
            <img src="{{ asset('assets/static/images/logo-smea.png') }}" alt="" height="65" width="65">
            <h4 class="ms-2 text-white d-none d-md-block">Sistem Informasi Praktik Anak Pada Instansi dan Industri</h4>
            <h4 class="ms-2 text-white d-block d-md-none">SIPAPII</h4>
        </div>
        <div class="btn-group mb-1">
            <div class="dropdown">
                <a class="dropdown me-1" type="button" id="dropdownProfile" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <div class="avatar avatar-lg">
                        @if (auth()->user()->profile_picture == null)
                            <img src="{{ asset('assets/static/images/faces/1.jpg') }}" alt="">
                        @else
                            <img src="{{ auth()->user()->profile_picture }}" alt="">
                        @endif
                        <div class="avatar-status bg-success"></div>
                    </div>
                </a>
                <div class="dropdown-menu mt-3" aria-labelledby="dropdownProfile">
                    @if (auth()->user()->role != 'siswa')
                        <a class="dropdown-item" href="#">Profile</a>
                        <a class="dropdown-item" href="#">Setting</a>
                        <div class="dropdown-divider"></div>
                    @endif
                    <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>
