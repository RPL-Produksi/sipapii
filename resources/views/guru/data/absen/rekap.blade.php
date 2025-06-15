@extends('layouts.app-3')
@section('title', 'Data Rekap Absensi Siswa')

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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Daftar Rekap Absensi Siswa</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered w-100 nowrap" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Hadir</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                        <th>Alpa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rekap as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item['nama'] }}</td>
                                            <td>{{ $item['kelas'] }}</td>
                                            <td>{{ $item['hadir'] }}</td>
                                            <td>{{ $item['izin'] }}</td>
                                            <td>{{ $item['sakit'] }}</td>
                                            <td>{{ $item['alpa'] }}</td>
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

    <div class="modal fade" id="jarakAbsenModal" tabindex="-1" role="dialog" aria-labelledby="jarakAbsenModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jarakAbsenModalTitle">Lokasi Absen Siswa</h5>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <div id="map" style="height: 400px; width: 100%;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
        $(document).ready(function() {
            $('#table-1').DataTable({
                responsive: true,
            });
        });
    </script>
@endpush
