@extends('layouts.app')
@section('title', 'Kelola Tahun Ajar')

@push('css')
    {{-- CSS Only For This Page --}}
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/dataTables.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
@endpush

@section('content')
    <section class="row">
        @include('template.feedback')

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Tahun Ajar</h4>
                        <div class="float-right">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTahunAjar"><i
                                    class="fa-regular fa-add"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered nowrap w-100" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Tahun Ajar</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="addTahunAjar" tabindex="-1" role="dialog" aria-labelledby="addTahunAjarTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTahunAjarTitle">Tambah Tahun Ajar</h5>
                </div>
                <form action="{{ route('admin.pengelolaan.tahun-ajar.add') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" id="tahun_ajar" name="tahun_ajar"
                                placeholder="Masukan Tahun Ajar (2024/2025)" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">
                            <span>Batal</span>
                        </button>
                        <button type="submit" class="btn btn-success ms-1" data-loading="true">
                            <span>Tambah</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTahunAjarModal" tabindex="-1" role="dialog" aria-labelledby="editTahunAjarModaTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTahunAjarModalTitle">Tambah Tahun Ajar</h5>
                </div>
                <form method="POST" id="editTahunAjarForm">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" name="tahun_ajar"
                                placeholder="Masukan Tahun Ajar (2024/2025)" required id="edit-tahun-ajar">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">
                            <span>Batal</span>
                        </button>
                        <button type="submit" class="btn btn-success ms-1" data-loading="true">
                            <span>Ubah</span>
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
    <script src="{{ asset('assets/extensions/filepond/filepond.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-1').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.pengelolaan.tahun-ajar.data') }}",
                    data: function(e) {
                        return e;
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
                        data: 'tahun_ajar',
                        orderable: true,
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            let showUrl =
                                '{{ route('admin.pengelolaan.tahun-ajar.kelas', ':id') }}';

                            let showBtn =
                                `<a href='${showUrl.replace(':id', row.id)}' class="btn btn-success"><i class="fa-regular fa-eye"></i></a>`;
                            let editBtn =
                                `<button onclick='edit("${row.id}")' class="btn btn-primary"><i class="fa-regular fa-edit"></i></button>`;
                            let deleteBtn =
                                `<button onclick="confirmDelete('${row.id}')" class="btn btn-danger"><i class="fa-regular fa-trash"></i></button>`;

                            let alumniBtn = '';
                            if (!row.is_alumni) {
                                alumniBtn =
                                    `<button onclick="confirmAlumnikan('${row.id}')" class="btn btn-warning text-white fw-bold">Alumnikan</button>`;
                            }

                            return `<div class="d-flex flex-row gap-2">${showBtn}${editBtn}${deleteBtn}${alumniBtn}</div>`;
                        }

                    }
                ],
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
        const edit = (id) => {
            $.getJSON(`${window.location.origin}/admin/pengelolaan/tahun-ajar/data/${id}`, (data) => {
                const updateUrl = `{{ route('admin.pengelolaan.tahun-ajar.edit', ':id') }}`

                $('#editTahunAjarForm').attr('action', updateUrl.replace(':id', id))
                $('#edit-tahun-ajar').val(data.tahun_ajar);

                const myModal = new bootstrap.Modal(document.getElementById('editTahunAjarModal'));
                myModal.show();
            })
        }

        const confirmDelete = (id) => {
            const deleteUrl = "{{ route('admin.pengelolaan.tahun-ajar.delete', ':id') }}"

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengembalikan data yang sudah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl.replace(':id', id);
                    Swal.fire("Success, Tahun ajar berhasil dihapus!", {
                        icon: "success",
                    });
                } else {
                    Swal.fire("Penghapusan tahun ajar dibatalkan!");
                }
            });
        }

        const confirmAlumnikan = (id) => {
            const alumniUrl = '{{ route('admin.pengelolaan.tahun-ajar.alumni', ':id') }}?type=aktif';

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengembalikan siswa yang di alumnikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, alumnikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = alumniUrl.replace(':id', id);
                    Swal.fire("Success, Siswa berhasil dialumnikan!", {
                        icon: "success",
                    });
                } else {
                    Swal.fire("Pengalumnikan siswa dibatalkan!");
                }
            });
        }
    </script>
@endpush
