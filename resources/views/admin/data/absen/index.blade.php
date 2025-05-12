@extends('layouts.app')

@php
    if (Request::query('type') == 'hari-ini') {
        $title = 'Data Absensi Siswa';
    } elseif (Request::query('type') == 'all') {
        $title = 'Data Absensi Siswa';
    }
@endphp

@section('title', @$title)

@push('css')
    {{-- CSS Only For This Page --}}
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/dataTables.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/leaflet/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css') }}">
@endpush

@section('content')
    <section class="row">
        @include('template.feedback')

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        @if (Request::query('type') == 'hari-ini')
                            <h4 class="card-title">Daftar Absen Siswa Hari Ini</h4>
                        @elseif (Request::query('type') == 'all')
                            <h4 class="card-title">Daftar Absensi Siswa</h4>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if (Request::query('type') == 'all')
                        <div class="row mb-5">
                            <div class="col-6">
                                <label for="">Tanggal Awal</label>
                                <input type="date" name="tanggal_awal" id="tanggalAwal" class="form-control">
                            </div>
                            <div class="col-6">
                                <label for="">Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" id="tanggalAkhir" class="form-control">
                            </div>
                            <div class="col-12 mt-2">
                                <button class="btn btn-primary w-100" id="btnFilter"><i
                                        class="fa-regular fa-filter me-2"></i>Filter</button>
                            </div>
                        </div>
                    @endif
                    <table class="table table-bordered nowrap w-100" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th class="text-center">Jam Masuk</th>
                                <th class="text-center">Jam Pulang</th>
                                <th>Jarak Absen</th>
                                <th>Instansi</th>
                                <th class="text-center">Keterangan</th>
                                <th>Pembimbing</th>
                                <th>Guru Mapel PKL</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

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
    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('assets/extensions/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js') }}"></script>
    <script>
        let map;
        const showJarakAbsen = (id, latSis, longSis) => {
            const type = '{{ Request::query('type') }}';
            $.getJSON(`${window.location.origin}/admin/siswa/absen/data/${id}?type=${type}`, (data) => {
                const latInstansi = data.siswa.penempatan.instansi.latitude;
                const longInstansi = data.siswa.penempatan.instansi.longitude;

                if (map) {
                    map.off();
                    map.remove();
                }

                map = L.map('map').setView([latInstansi, longInstansi], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                L.marker([latInstansi, longInstansi])
                    .addTo(map)
                    .bindPopup('Lokasi Instansi')
                    .openPopup();

                if (latSis !== null && longSis !== null) {
                    L.marker([latSis, longSis])
                        .addTo(map)
                        .bindPopup('Lokasi Anda');

                    const bounds = L.latLngBounds([
                        [latInstansi, longInstansi],
                        [latSis, longSis]
                    ]);
                    map.fitBounds(bounds);
                } else {
                    map.setView([latInstansi, longInstansi], 15);
                }

                L.circle([latInstansi, longInstansi], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: 500
                }).addTo(map);

                $('#jarakAbsenModal').on('shown.bs.modal', () => {
                    map.invalidateSize();
                });
            })
        };
    </script>
    <script>
        $(document).ready(function() {
            $('#table-1').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('admin.absen.siswa.data', ['type' => Request::query('type')]) }}",
                    data: function(e) {
                        e.tanggal_awal = $('#tanggalAwal').val();
                        e.tanggal_akhir = $('#tanggalAkhir').val();
                    }
                },
                order: [
                    [0, 'desc']
                ],
                columns: [{
                        data: null,
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            let pageInfo = $('#table-1').DataTable().page.info();
                            return meta.row + 1 + pageInfo.start;
                        }
                    },
                    {
                        data: 'tanggal',
                        orderable: false,
                    },
                    {
                        data: 'siswa.user.nama_lengkap',
                        orderable: false,
                    },
                    {
                        data: 'siswa.kelas.nama',
                        orderable: false,
                    },
                    {
                        data: 'jam_masuk',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return `<div class="badge ${row.status == 'Alpa' ? 'bg-danger' : 'bg-success'}">${data ?? '00:00'}</div>`;
                        }
                    },
                    {
                        data: 'jam_pulang',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return `<div class="badge ${row.status == 'Alpa' ? 'bg-danger' : 'bg-warning'}">${data ?? '00:00'}</div>`;
                        }
                    },
                    {
                        data: 'jarak',
                        orderable: false,
                    },
                    {
                        data: 'siswa.penempatan.instansi.nama',
                        orderable: false,
                    },
                    {
                        data: 'status',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return `<div class="badge ${data == 'Alpa' ? 'bg-danger' : 'bg-success'}">${data}</div>`;
                        }
                    },
                    {
                        data: 'siswa.pembimbingan.pembimbing.user.nama_lengkap',
                        orderable: false,
                    },
                    {
                        data: 'siswa.pembimbingan.guru_mapel_p_k_l.user.nama_lengkap',
                        orderable: false,
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return `
                                <div>
                                    <a type="button" 
                                    onclick="showJarakAbsen('${row.id}', ${row.latitude}, ${row.longitude})" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#jarakAbsenModal" 
                                    class="btn btn-primary">
                                    <i class="fa-regular fa-map-location-dot"></i>
                                    </a>
                                </div>
                            `;
                        }
                    }
                ],
                dom: "<'row'<'col-12 col-sm-3'l><'col-12 col-sm-9 text-end text-sm-start'>>" +
                    "<'row dt-row'<'col-12'tr>>" +
                    "<'row'<'col-12 col-sm-4 text-center text-sm-start'i><'col-12 col-sm-8 text-center text-sm-end'p>>",
                "language": {
                    "info": "Page _PAGE_ of _PAGES_",
                    "lengthMenu": "_MENU_ ",
                }
            });

            $('#btnFilter').on('click', function() {
                $('#table-1').DataTable().ajax.reload();
            });
        });
    </script>
@endpush
