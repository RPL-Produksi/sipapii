@extends('layouts.app')

@php
    if (Request::query('type') == 'all') {
        $title = 'Data Jurnal Siswa';
    } elseif (Request::query('type') == 'belum-validasi') {
        $title = 'Data Jurnal Siswa Belum Divalidasi';
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
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
    <section class="row">
        @include('template.feedback')

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex">
                        @if (Request::query('type') == 'all')
                            <h4 class="card-title">Daftar Jurnal Siswa</h4>
                        @elseif (Request::query('type') == 'belum-validasi')
                            <h4 class="card-title">Daftar Jurnal Siswa Belum Divalidasi</h4>
                        @endif
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
                    <table class="table table-bordered nowrap w-100" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Deskripsi Jurnal</th>
                                <th>Status</th>
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

    <div class="modal fade" id="detailJurnalModal" tabindex="-1" role="dialog" aria-labelledby="detailJurnalModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailJurnalModalTitle">Detail Jurnal Siswa</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label for="">Nama Lengkap</label>
                            <input type="text" class="form-control" id="detailNamaLengkap" disabled>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="">Kelas</label>
                            <input type="text" class="form-control" id="detailKelas" disabled>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="">Tanggal</label>
                            <input type="date" class="form-control" id="detailTanggal" disabled>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label for="">Guru Mapel PKL</label>
                            <input type="text" class="form-control" id="detailGuruMapelPkl" disabled>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">Deskripsi Jurnal</label>
                            <textarea name="" id="detailDeskripsiJurnal" class="form-control" rows="5" disabled></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">Status</label>
                            <input type="text" class="form-control" id="detailStatus" disabled>
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

    <div class="modal fade" id="editJurnalModal" tabindex="-1" role="dialog" aria-labelledby="editJurnalModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editJurnalModalTitle">Edit Jurnal Siswa</h5>
                </div>
                <form action="" method="POST" id="editJurnalForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="">Nama Lengkap</label>
                                <input type="text" class="form-control" id="editNamaLengkap" disabled>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="">Kelas</label>
                                <input type="text" class="form-control" id="editKelas" disabled>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="">Tanggal</label>
                                <input type="date" class="form-control" id="editTanggal" disabled>
                            </div>
                            <div class="col-12 col-md-6 mb-3">
                                <label for="">Guru Mapel PKL</label>
                                <input type="text" class="form-control" id="editGuruMapelPkl" disabled>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="">Deskripsi Jurnal</label>
                                <textarea name="" id="editDeskripsiJurnal" class="form-control" rows="5" disabled></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="">Status</label>
                                <select name="validasi" id="editStatusJurnal" class="choices form-select">
                                    <option value="Belum Divalidasi">Belum Divalidasi</option>
                                    <option value="Divalidasi">Divalidasi</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link text-primary text-decoration-none"
                            data-bs-dismiss="modal">
                            <span>Batal</span>
                        </button>
                        <button type="submit" class="btn btn-success" data-loading="true">
                            <span>Simpan</span>
                        </button>
                    </div>
                </form>
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
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-1').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('admin.jurnal.siswa.data', ['type' => Request::query('type')]) }}",
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
                        data: 'guru_mapel_pkl.user.nama_lengkap',
                        orderable: false,
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            let detailBtn =
                                `<button onclick="show('${row.id}')" class='btn btn-warning me-2 text-white'><i class='fa-regular fa-eye'></i></button>`
                            let editBtn =
                                `<button onclick="edit('${row.id}')" class='btn btn-primary'><i class='fa-regular fa-pen-to-square'></i></button>`

                            return `${detailBtn}${editBtn}`;
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
            const type = '{{ Request::query('type') }}';
            $.getJSON(`${window.location.origin}/admin/siswa/jurnal/data/${id}?type=${type}`, (data) => {
                $('#detailNamaLengkap').val(data.siswa.user.nama_lengkap);
                $('#detailKelas').val(data.siswa.kelas.nama);
                $('#detailTanggal').val(convertTanggal(data.tanggal));
                $('#detailGuruMapelPkl').val(data.guru_mapel_pkl.user.nama_lengkap);
                $('#detailDeskripsiJurnal').val(data.deskripsi_jurnal);
                $('#detailStatus').val(data.validasi);


                const myModal = new bootstrap.Modal(document.getElementById('detailJurnalModal'));
                myModal.show();
            })
        }

        let editStatusJurnal;
        $(document).ready(() => {
            editStatusJurnal = new Choices('#editStatusJurnal', {
                searchEnabled: false,
            });
        })

        const edit = (id) => {
            const type = '{{ Request::query('type') }}';
            $.getJSON(`${window.location.origin}/admin/siswa/jurnal/data/${id}?type=${type}`, (data) => {
                const updateUrl = `{{ route('admin.jurnal.siswa.edit.status', ':id') }}`

                $('#editJurnalForm').attr('action', updateUrl.replace(':id', id));
                $('#editNamaLengkap').val(data.siswa.user.nama_lengkap);
                $('#editKelas').val(data.siswa.kelas.nama);
                $('#editTanggal').val(convertTanggal(data.tanggal));
                $('#editGuruMapelPkl').val(data.guru_mapel_pkl.user.nama_lengkap);
                $('#editDeskripsiJurnal').val(data.deskripsi_jurnal);
                editStatusJurnal.setChoiceByValue(data.validasi);


                const myModal = new bootstrap.Modal(document.getElementById('editJurnalModal'));
                myModal.show();
            })
        }
    </script>
@endpush
