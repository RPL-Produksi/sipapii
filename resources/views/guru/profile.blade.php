@extends('layouts.app-3')
@section('title', 'Profil Guru')

@push('css')
    {{-- CSS Only For This Page --}}
@endpush

@section('content')
    <div class="row">
        @include('template.feedback')

        <div class="col-12 col-md-12 col-lg-8 order-2 order-md-2 order-lg-1 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Akun Saya</h3>
                </div>
                <form action="{{ route('guru.profile.edit') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nip">NIP</label>
                            <input type="text" id="nip" class="form-control" value="{{ $guru->nip }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="namaLengkap">Nama Lengkap</label>
                            <input type="text" id="namaLengkap" class="form-control"
                                value="{{ $guru->user->nama_lengkap }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ $guru->user->email }}" placeholder="Masukan email anda">
                        </div>
                        <div class="form-group">
                            <label for="nomor_wa">Nomor Whatsapp</label>
                            <input type="nomor_wa" name="nomor_wa" id="nomor_wa" class="form-control"
                                value="{{ $guru->nomor_wa }}" placeholder="Masukan nomor wa anda">
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex align-items-center justify-content-end">
                            <button type="submit" class="btn btn-success" data-loading="true">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-4 order-1 order-md-1 order-lg-2 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title text-center">Foto Profile</h3>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="row">
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            @if ($guru->user->profile_picture == null)
                                <img src="{{ asset('assets/static/images/faces/1.jpg') }}" alt=""
                                    class="img-fluid w-50 rounded-circle font-weight-bold">
                            @else
                                <img src="{{ $guru->user->profile_picture }}" alt=""
                                    class="img-fluid w-50 rounded-circle font-weight-bold">
                            @endif
                        </div>
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <form action="{{ route('guru.profile.picture.edit') }}" enctype="multipart/form-data"
                                method="POST" id="changeProfileForm">
                                @csrf
                                <input type="file" name="foto_profile" class="form-control mt-4 d-none"
                                    id="inputChangeProfile">
                            </form>
                            <button onclick="changeProfile()" class="btn btn-primary btn-sm text-center mt-4"
                                data-loading="true">Ubah Profile</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 order-3 order-md-3 order-lg-3 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title">Ubah Password</h4>
                </div>
                <form action="{{ route('password.change') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="password">Password Lama</label>
                            <input type="password" name="old_password" class="form-control" id="password"
                                placeholder="Masukan password lama">
                        </div>
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" name="new_password" class="form-control" id="newPassword"
                                placeholder="Masukan password baru">
                        </div>
                        <div class="form-group">
                            <label for="password">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                id="passwordConfirmation" placeholder="Masukan konfirmasi password">
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex align-items-center justify-content-end">
                            <button type="submit" class="btn btn-success" data-loading="true">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
    <script>
        const changeProfile = () => {
            const inputChangeProfile = document.getElementById("inputChangeProfile")
            const changeProfileForm = document.getElementById("changeProfileForm")
            inputChangeProfile.click()

            inputChangeProfile.addEventListener("change", () => {
                changeProfileForm.submit()
            })
        }
    </script>
@endpush
