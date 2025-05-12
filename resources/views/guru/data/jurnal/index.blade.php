@extends('layouts.app')
@section('title', 'Data Jurnal Siswa')

@push('css')
    {{-- CSS Only For This Page --}}
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/dataTables.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
    <section class="row">
        @include('template.feedback')

        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title">Daftar Jurnal Siswa</h4>
                                <div class="float-right d-flex align-items-center">
                                    <span class="fw-bold me-2">Belum Divalidasi :</span>
                                    <span class="badge bg-warning">{{ $jurnalNotValidasiCount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
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
                            <table class="table table-bordered w-100 nowrap" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Tanggal</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Deskrpisi Jurnal</th>
                                        <th>Status</th>
                                        <th>Instansi</th>
                                        <th>Pembimbing</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="detailJurnalModal" tabindex="-1" role="dialog" aria-labelledby="detailJurnalModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailJurnalModalTitle">Detail Jurnal Siswa</h5>
                </div>
                <div class="modal-body" >
                    <div class="row">
                        <div class="col-sm-4">
                            <h6>Nama Siswa</h4>
                        </div>
                        <div class="col-sm-8">
                            <p id="detailNamaLengkap"></p>
                        </div>
                        <div class="col-sm-4">
                            <h6>Kelas</h4>
                        </div>
                        <div class="col-sm-8">
                            <p id="detailKelas"></p>
                        </div>
                        <div class="col-sm-4">
                            <h6>Tanggal</h4>
                        </div>
                        <div class="col-sm-8">
                            <p id="detailTanggal"></p>
                        </div>
                        <div class="col-sm-4">
                            <h6>Instansi</h4>
                        </div>
                        <div class="col-sm-8">
                            <p id="detailInstansi"></p>
                        </div>
                        <div class="col-sm-4">
                            <h6>Pembimbing</h4>
                        </div>
                        <div class="col-sm-8">
                            <p id="detailPembimbing"></p>
                        </div>
                        <div class="col-sm-4">
                            <h6>Status Jurnal</h4>
                        </div>
                        <div class="col-sm-8 mb-3" id="detailStatus"></div>
                        <div class="col-sm-12">
                            <h6>Deskripsi Jurnal</h4>
                                <textarea id="detailDeskripsiJurnal" class="form-control" rows="5" disabled></textarea>
                        </div>
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

    <form id="jurnalActionForm" method="POST" style="display: none;">
        @csrf
    </form>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-1').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('guru.siswa.jurnal.data') }}",
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
                        data: 'deskripsi_jurnal',
                        orderable: false,
                    },
                    {
                        data: 'validasi',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            if (data == 'Belum Divalidasi') {
                                return `<div class="badge bg-warning">${data}</div>`
                            } else if (data == 'Divalidasi') {
                                return `<div class="badge bg-success">${data}</div>`
                            } else if (data == 'Ditolak' || data == 'Tidak Mengisi') {
                                return `<div class="badge bg-danger">${data}</div>`

                            };
                        }
                    },
                    {
                        data: 'siswa.penempatan.instansi.nama',
                        orderable: false,
                    },
                    {
                        data: 'siswa.pembimbingan.pembimbing.user.nama_lengkap',
                        orderable: false,
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            let detailBtn =
                                `<button onclick="show('${row.id}')" class='btn btn-warning me-2 text-white'><i class='fa-regular fa-eye'></i></button>`;
                            let divalidasiBtn =
                                `<button onclick="validasiConfirmation('${row.id}')" class='btn btn-success me-2'><i class='fa-regular fa-check'></i></button>`;
                            let ditolakBtn =
                                `<button onclick="tolakConfirmation('${row.id}')" class='btn btn-danger'><i class='fa-regular fa-close'></i></button>`;

                            return (
                                `<div class="d-flex">
                                  ${detailBtn}
                                  ${row.validasi == 'Belum Divalidasi' ? divalidasiBtn + ditolakBtn : ''}
                                  ${row.validasi == 'Divalidasi' ? ditolakBtn : ''}
                                </div>`
                            );
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
    <script>
        const convertTanggal = (tanggal) => {
            const [dd, mm, yyyy] = tanggal.split('-');
            return `${yyyy}-${mm}-${dd}`;
        };

        const show = (id) => {
            $.getJSON(`${window.location.origin}/guru/siswa/jurnal/data/${id}`, (data) => {
                $('#detailNamaLengkap').text(data.siswa.user.nama_lengkap);
                $('#detailKelas').text(data.siswa.kelas.nama);
                $('#detailTanggal').text(convertTanggal(data.tanggal));
                $('#detailInstansi').text(data.siswa.penempatan.instansi.nama);
                $('#detailPembimbing').text(data.siswa.pembimbingan.pembimbing.user.nama_lengkap);
                $('#detailDeskripsiJurnal').text(data.deskripsi_jurnal);
                $('#detailStatus').html(
                    `<div class="badge ${data.validasi == 'Belum Divalidasi' ? 'bg-warning' : data.validasi == 'Divalidasi' ? 'bg-success' : 'bg-danger'
                        }">${data.validasi}</div>`
                );


                const myModal = new bootstrap.Modal(document.getElementById('detailJurnalModal'));
                myModal.show();
            })
        }

        const validasiConfirmation = (id) => {
            Swal.fire({
                title: 'Validasi Jurnal',
                text: "Apakah anda yakin ingin memvalidasi jurnal ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Validasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('jurnalActionForm');
                    form.action = `${window.location.origin}/guru/siswa/jurnal/${id}/check?status=validasi`;
                    form.submit();
                }
            });
        }

        const tolakConfirmation = (id) => {
            Swal.fire({
                title: 'Tolak Jurnal',
                text: "Apakah anda yakin ingin menolak jurnal ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('jurnalActionForm');
                    form.action = `${window.location.origin}/guru/siswa/jurnal/${id}/check?status=ditolak`;
                    form.submit();
                }
            });
        }
    </script>
@endpush
