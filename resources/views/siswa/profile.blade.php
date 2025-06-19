@extends('layouts.app-2')
@section('title', 'Profil Siswa')

@push('css')
    {{-- CSS Only For This Page --}}
    <link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/leaflet/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css') }}">
@endpush

@section('content')
    <div class="px-4 py-4">
        <div class="row">
            @include('template.feedback')

            <div class="col-12 col-md-12 col-lg-8 order-2 order-md-2 order-lg-1 mt-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h3 class="card-title">Akun Saya</h3>
                    </div>
                    <form action="{{ route('siswa.profile.fields.add') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nis">NIS</label>
                                <input type="text" id="nis" class="form-control" value="{{ $siswa->nis }}"
                                    disabled>
                            </div>
                            <div class="form-group">
                                <label for="namaLengkap">Nama Lengkap</label>
                                <input type="text" id="namaLengkap" class="form-control"
                                    value="{{ $siswa->user->nama_lengkap }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ $siswa->user->email }}" placeholder="Masukan email anda">
                            </div>
                            <div class="form-group">
                                <label for="nomor_wa">Nomor WA</label>
                                <input type="nomor_wa" name="nomor_wa" id="nomor_wa" class="form-control"
                                    value="{{ $siswa->nomor_wa }}" placeholder="Masukan nomor wa anda">
                            </div>
                            <div class="form-group">
                                <label for="jenisKelamin">Jenis Kelamin</label>
                                <input type="text" class="form-control" value="{{ $siswa->jenis_kelamin }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <input type="text" class="form-control" value="{{ $siswa->kelas->nama }}" disabled>
                            </div>
                            <div class="form-group">
                                <label for="tahun">Tahun Ajar</label>
                                <input type="text" class="form-control" value="{{ $siswa->tahunAjar->tahun_ajar }}"
                                    disabled>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex align-items-center justify-content-end">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-4 order-1 order-md-1 order-lg-2 mt-3">
                <div class="row">
                    <div class="col-12">
                        <div class="card h-100">
                            <div class="card-header">
                                <h3 class="card-title text-center">Foto Profile</h3>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center justify-content-center">
                                        @if ($siswa->user->profile_picture == null)
                                            <img src="{{ asset('assets/static/images/faces/1.jpg') }}" alt=""
                                                class="img-fluid w-50 rounded-circle font-weight-bold">
                                        @else
                                            <img src="{{ $siswa->user->profile_picture }}" alt=""
                                                class="img-fluid w-50 rounded-circle font-weight-bold">
                                        @endif
                                    </div>
                                    <div class="col-12 d-flex align-items-center justify-content-center">
                                        <form action="{{ route('siswa.profile.picture.edit') }}"
                                            enctype="multipart/form-data" method="POST" id="changeProfileForm">
                                            @csrf
                                            <input type="file" name="foto_profile" class="form-control mt-4 d-none"
                                                id="inputChangeProfile">
                                        </form>
                                        <button onclick="changeProfile()"
                                            class="btn btn-primary btn-sm text-center mt-4">Ubah
                                            Profile</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="card h-100">
                            <div class="card-header">
                                <h4 class="card-title text-center">Penempatan & Pembimbingan</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <h6>Instansi: <a type="button" class="text-primary"
                                            data-bs-target="#instansiDetailModal"
                                            data-bs-toggle="modal"><span>{{ $siswa->penempatan->instansi->nama ?? 'Belum ditempatkan' }}</span></a>
                                    </h6>
                                </div>
                                <div class="form-group">
                                    <h6>Guru Pembimbing: <a type="button" class="text-primary"
                                            data-bs-target="#pembimbingDetailModal"
                                            data-bs-toggle="modal"><span>{{ $siswa->pembimbingan->pembimbing->user->nama_lengkap ?? 'Data tidak tersedia' }}</span></a>
                                    </h6>
                                </div>
                                <div class="form-group">
                                    <h6>Guru Mapel PKL: <a type="button" class="text-primary"
                                            data-bs-target="#guruMapelPklModal"
                                            data-bs-toggle="modal"><span>{{ $siswa->pembimbingan->guruMapelPKL->user->nama_lengkap ?? 'Data tidak tersedia' }}</span></a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 order-3 order-md-3 order-lg-3">
                <div class="card h-100 mt-3">
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
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 7rem;"></div>

    <div class="modal fade" id="instansiDetailModal" tabindex="-1" role="dialog"
        aria-labelledby="instansiDetailModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="instansiDetailModalTitle">Detail Instansi</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama Instansi</label>
                        <input type="text" class="form-control" value="{{ @$siswa->penempatan->instansi->nama }}"
                            disabled>
                    </div>
                    <div class="form-group">
                        <label for="nama">Domisili Instansi</label>
                        <input type="text" class="form-control" value="{{ @$siswa->penempatan->instansi->domisili }}"
                            disabled>
                    </div>
                    <div class="form-group">
                        <label for="nama">Alamat Instansi</label>
                        <textarea class="form-control" disabled>{{ @$siswa->penempatan->instansi->alamat }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="nama">Lokasi Instansi</label>
                        <div id="map" style="height: 200px; width: 100%;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">
                        <span>Tutup</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pembimbingDetailModal" tabindex="-1" role="dialog"
        aria-labelledby="pembimbingDetailModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pembimbingDetailModalTitle">Detail Pembimbing</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group text-center">
                        @if (@$siswa->pembimbingan->pembimbing->user->profile_picture == null)
                            <img src="{{ asset('assets/static/images/faces/1.jpg') }}" alt=""
                                class="img-fluid w-50 rounded-circle font-weight-bold">
                        @else
                            <img src="{{ @$siswa->pembimbingan->pembimbing->user->profile_picture }}" alt=""
                                class="img-fluid w-50 rounded-circle font-weight-bold">
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="nip">NIP Pembimbing</label>
                        <input type="text" class="form-control" value="{{ @$siswa->pembimbingan->pembimbing->nip }}"
                            disabled>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Pembimbing</label>
                        <input type="text" class="form-control"
                            value="{{ @$siswa->pembimbingan->pembimbing->user->nama_lengkap }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="nomor_wa">Nomor Whatsapp</label>
                        <input type="text" class="form-control"
                            value="{{ @$siswa->pembimbingan->pembimbing->nomor_wa }}" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">
                        <span>Tutup</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="guruMapelPklModal" tabindex="-1" role="dialog"
        aria-labelledby="guruMapelPklModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="guruMapelPklModalTitle">Detail Guru Mapel PKL</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group text-center">
                        @if (@$siswa->pembimbingan->guruMapelPKL->user->profile_picture == null)
                            <img src="{{ asset('assets/static/images/faces/1.jpg') }}" alt=""
                                class="img-fluid w-50 rounded-circle font-weight-bold">
                        @else
                            <img src="{{ @$siswa->pembimbingan->guruMapelPKL->user->profile_picture }}" alt=""
                                class="img-fluid w-50 rounded-circle font-weight-bold">
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="nip">NIP Guru Mapel PKL</label>
                        <input type="text" class="form-control"
                            value="{{ @$siswa->pembimbingan->guruMapelPKL->nip }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Guru Mapel PKL</label>
                        <input type="text" class="form-control"
                            value="{{ @$siswa->pembimbingan->guruMapelPKL->user->nama_lengkap }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="nomor_wa">Nomor Whatsapp</label>
                        <input type="text" class="form-control"
                            value="{{ @$siswa->pembimbingan->guruMapelPKL->nomor_wa }}" disabled>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">
                        <span>Tutup</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
    <script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script src="{{ asset('assets/extensions/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('assets/extensions/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js') }}"></script>
    <script>
        let choices = document.querySelectorAll(".choices")
        let initChoice
        for (let i = 0; i < choices.length; i++) {
            if (choices[i].classList.contains("multiple-remove")) {
                initChoice = new Choices(choices[i], {
                    delimiter: ",",
                    editItems: true,
                    maxItemCount: -1,
                    removeItemButton: true,
                })
            } else {
                initChoice = new Choices(choices[i])
            }
        }
    </script>
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
    <script>
        $(document).ready(() => {
            const latInstansi = {{ @$siswa->penempatan->instansi->latitude }}
            const longInstansi = {{ @$siswa->penempatan->instansi->longitude }}

            map = L.map('map').setView([latInstansi, longInstansi], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([latInstansi, longInstansi])
                .addTo(map)

            $('#instansiDetailModal').on('shown.bs.modal', () => {
                map.invalidateSize();
            });
        })
    </script>
@endpush
