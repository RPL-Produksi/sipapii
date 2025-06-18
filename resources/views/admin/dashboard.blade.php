@extends('layouts.app')
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
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon purple mb-2">
                                        <i class="fa-regular fa-location-smile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">PKL Luar Kota</h6>
                                    <h6 class="font-extrabold mb-0">{{ $pklLuarKota }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="fa-regular fa-location-check"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">PKL Dalam Kota</h6>
                                    <h6 class="font-extrabold mb-0">{{ $pklDalamKota }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="fa-regular fa-arrow-progress"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Belum Ditempatkan</h6>
                                    <h6 class="font-extrabold mb-0">{{ $belumDitempatkan }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="fa-regular fa-users"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Siswa</h6>
                                    <h6 class="font-extrabold mb-0">{{ $siswa }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Statistik Absensi</h4>
                                </div>
                                <div class="card-body">
                                    <div id="chart-absensi-siswa"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Penempatan Terbaru</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered nowrap w-100" id="table-2">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama</th>
                                                <th>Kelas</th>
                                                <th>Instansi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($penempatan as $item)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $item->siswa->user->nama_lengkap }}</td>
                                                    <td>{{ $item->siswa->kelas->nama }}</td>
                                                    <td>{{ $item->instansi->nama }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Absen Terbaru</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered nowrap w-100" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama</th>
                                        <th>Instansi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($absen as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->user->nama_lengkap }}</td>
                                            <td>{{ $item->penempatan->instansi->nama }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Siswa PKL</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart-domisili-pkl"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
    <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-1').DataTable({
                responsive: true,
                scrollX: true,
                dom: "<'row'<'col-12 col-sm-3'l><'col-12 col-sm-9 text-end text-sm-start'f>>" +
                    "<'row dt-row'<'col-12'tr>>" +
                    "<'row'<'col-12 col-sm-4 text-center text-sm-start'i><'col-12 col-sm-8 text-center text-sm-end'p>>",
                "language": {
                    "info": "Page _PAGE_ of _PAGES_",
                    "lengthMenu": "_MENU_ ",
                }
            });

            $('#table-2').DataTable({
                responsive: true,
                scrollX: true,
                dom: "<'row'<'col-12 col-sm-3'l><'col-12 col-sm-9 text-end text-sm-start'f>>" +
                    "<'row dt-row'<'col-12'tr>>" +
                    "<'row'<'col-12 col-sm-4 text-center text-sm-start'i><'col-12 col-sm-8 text-center text-sm-end'p>>",
                "language": {
                    "info": "Page _PAGE_ of _PAGES_",
                    "lengthMenu": "_MENU_ ",
                }
            });
        });
    </script>
    <script>
        const chartPklDomisili = new ApexCharts(
            document.getElementById("chart-domisili-pkl"), {
                series: [{{ $pklLuarKota }}, {{ $pklDalamKota }}],
                labels: ["Luar Kota", "Dalam Kota"],
                colors: ["#435ebe", "#55c6e8"],
                chart: {
                    type: "donut",
                    width: "100%",
                    height: "350px",
                },
                legend: {
                    position: "bottom",
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: "30%",
                        },
                    },
                },
            }
        )
        chartPklDomisili.render();
    </script>
    <script>
        const absenMasuk = @json($masukData);
        const absenPulang = @json($pulangData);

        var optionsAbsensiSiswa = {
            annotations: {
                position: "back",
            },
            dataLabels: {
                enabled: false,
            },
            chart: {
                type: "bar",
                height: 300,
            },
            fill: {
                opacity: 1,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded',
                }
            },
            series: [{
                    name: "Masuk",
                    data: absenMasuk,
                },
                {
                    name: "Pulang",
                    data: absenPulang,
                }
            ],
            colors: ["#435ebe", "#55c6e8"],
            xaxis: {
                categories: [
                    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                ],
            },
            legend: {
                position: 'top'
            }
        };

        var chartAbsensiSiswa = new ApexCharts(
            document.querySelector("#chart-absensi-siswa"),
            optionsAbsensiSiswa
        );
        chartAbsensiSiswa.render();
    </script>
@endpush
