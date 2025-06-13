@extends('layouts.app-3')
@section('title', 'Dashboard')

@push('css')
    {{-- CSS Only For This Page --}}
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/dataTables.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet">
@endpush

@section('content')
    <section class="row">
        @include('template.feedback')

        <div class="col-12">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon bg-success mb-2">
                                        <i class="fa-regular fa-check-to-slot"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Sudah Absen</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="font-extrabold mb-0">{{ $sudahAbsen }}</h6>
                                        <span class="text-success">Hari Ini</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon bg-danger mb-2">
                                        <i class="fa-regular fa-xmark-to-slot"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Belum Absen</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="font-extrabold mb-0">{{ $belumAbsen }}</h6>
                                        <span class="text-danger">Hari Ini</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon bg-primary mb-2">
                                        <i class="fa-regular fa-book-journal-whills"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Jurnal Siswa</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <h6 class="font-extrabold mb-0">{{ $jurnalHariIni }}</h6>
                                        <span class="text-primary">Hari Ini</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon bg-warning mb-2">
                                        <i class="fa-regular fa-users"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Siswa</h6>
                                    <h6 class="font-extrabold mb-0">{{ $totalSiswa }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Absensi Terbaru</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered w-100 nowrap" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Tanggal</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Pulang</th>
                                        <th>Jarak Absen</th>
                                        <th>Foto Absen</th>
                                        <th>Instansi</th>
                                        <th>Keterangan</th>
                                        <th>Pembimbing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($absensi as $item)
                                        @php
                                            $absen = $item->absen->first();
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $absen->tanggal ?? now()->format('d-m-Y') }}</td>
                                            <td>{{ $item->user->nama_lengkap }}</td>
                                            <td>{{ $item->kelas->nama }}</td>
                                            <td>
                                                <span class="badge bg-success">
                                                    {{ $absen->jam_masuk ?? 'Belum Absen Masuk' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    {{ $absen->jam_pulang ?? 'Belum Absen Pulang' }}
                                                </span>
                                            </td>
                                            <td>{{ $absen->jarak ?? '0 M' }}</td>
                                            <td>
                                                <div>
                                                    <a type="button" onclick="previewImage(this.href)"
                                                        data-bs-toggle="modal" data-bs-target="#previewImageModal"
                                                        href="{{ $absen->foto_masuk ?? asset('assets/static/images/faces/1.jpg') }}"
                                                        class="badge bg-success mx-1">Masuk</a>
                                                    <a type="button" onclick="previewImage(this.href)"
                                                        data-bs-toggle="modal" data-bs-target="#previewImageModal"
                                                        href="{{ $absen->foto_pulang ?? asset('assets/static/images/faces/1.jpg') }}"
                                                        class="badge bg-warning mx-1">Pulang</a>
                                                </div>
                                            </td>
                                            <td>{{ $item->penempatan->instansi->nama ?? '-' }}</td>
                                            <td>{{ $absen->status ?? '-' }}</td>
                                            <td>{{ $item->pembimbingan->guruMapelPKL->user->nama_lengkap ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="previewImageModal" tabindex="-1" role="dialog" aria-labelledby="previewImageModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewImageModalTitle">Preview Foto Absen</h5>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <img src="" alt="Image Preview" class="img-fluid rounded-3" loading="lazy">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        <span>Tutup</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script>
        $('#table-1').DataTable({
            responsive: true,
            scrollX: true,
        })
    </script>
    <script>
        const previewImage = (src) => {
            const modal = document.getElementById('previewImageModal');
            const img = modal.querySelector('img');

            if (src == `${window.location.origin}/siswa/null`) {
                img.src = "{{ asset('assets/static/images/faces/1.jpg') }}"
            } else {
                img.src = src;
            }
        }
    </script>
@endpush
