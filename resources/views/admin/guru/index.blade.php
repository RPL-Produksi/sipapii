@extends('layouts.app')
@section('title', 'Data Guru Mapel PKL & Pembimbing')

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
                        <h4 class="card-title">Daftar Guru Mapel PKL & Pembimbing</h4>
                        <div class="float-right">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addGuruModal"><i
                                    class="fa-regular fa-add"></i></button>
                            <button data-bs-toggle="modal" data-bs-target="#importGuruModal" class="btn btn-warning"><i
                                    class="fa-regular fa-file-import text-white"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered nowrap w-100" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                {{-- <th>Foto Profile</th> --}}
                                <th>Nama</th>
                                <th>Nomor Whatsapp</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="showGuruModal" tabindex="-1" role="dialog" aria-labelledby="showGuruModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showGuruModalTitle">Credentials Guru</h5>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="show-username" name="username"
                                placeholder="Masukan Username" required disabled>
                        </div>
                        <div class="form-group">
                            <label for="Password">Password</label>
                            <input type="text" class="form-control" id="show-password" name="password"
                                placeholder="Masukan Password" required disabled>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                            <span>Tutup</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addGuruModal" tabindex="-1" role="dialog" aria-labelledby="addGuruModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGuruModalTitle">Tambah Guru</h5>
                </div>
                <form action="{{ route('admin.guru.add') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Guru</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                                placeholder="Masukan Nama Guru" required>
                        </div>
                        <div class="form-group">
                            <label for="nomor_wa">Nomor Whatsapp</label>
                            <input type="text" class="form-control" id="nomor_wa" name="nomor_wa"
                                placeholder="Masukan Nomor Whatsapp" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">
                            <span>Batal</span>
                        </button>
                        <button type="submit" class="btn btn-success ms-1">
                            <span>Tambah</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editGuruModal" tabindex="-1" role="dialog" aria-labelledby="editGuruModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGuruModalTitle">Edit Guru</h5>
                </div>
                <form method="POST" id="form-edit-guru">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Guru</label>
                            <input type="text" class="form-control" id="edit-nama-guru" name="nama_lengkap"
                                placeholder="Masukan Nama Guru" required>
                        </div>
                        <div class="form-group">
                            <label for="nomor_wa">Nomor Whatsapp</label>
                            <input type="text" class="form-control" id="edit-nomor-wa-guru" name="nomor_wa"
                                placeholder="Masukan Nomor Whatsapp" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">
                            <span>Batal</span>
                        </button>
                        <button type="submit" class="btn btn-success ms-1">
                            <span>Ubah</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importGuruModal" tabindex="-1" role="dialog" aria-labelledby="importGuruModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importGuruModalTitle">Import Data Kelas</h5>
                </div>
                <form action="{{ route('admin.guru.import') }}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="mb-2">File CSV | Excel</label>
                            <input type="file" name="file" id="file" class="basic-filepond"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                        </div>
                        <div class="form-group">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-block">
                                    <label for="table">Contoh Table</label>
                                    <span class="text-danger">*Header jangan di hapus</span>
                                    <p class="text-secondary">Donwload contoh di sebelah -></p>
                                </div>
                                <a href="{{ asset('assets/import/Import Kelas (Example).xlsx') }}"
                                    class="btn btn-danger float-right mb-2"><i class="fa-regular fa-download"></i></a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Nama_Lengkap</th>
                                            <th>Nomor_Whatsapp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>John Doe</td>
                                            <td>08123123123</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">...</td>
                                            <td>...</td>
                                            <td>...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">
                            <span>Batal</span>
                        </button>
                        <button type="submit" class="btn btn-success ms-1">
                            <span>Import</span>
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
                scrollX: true,
                ajax: {
                    url: "{{ route('admin.guru.data') }}",
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
                        data: 'nama_lengkap',
                        orderable: true,
                    },
                    {
                        data: 'guru.nomor_wa',
                        orderable: true,
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            let showBtn =
                                `<button onclick="show('${row.id}')" class="btn btn-warning text-white"><i class="fa-regular fa-eye"></i></button>`;
                            let editBtn =
                                `<button onclick="edit('${row.id}')" class="btn btn-primary"><i class="fa-regular fa-edit"></i></button>`;
                            let deleteBtn =
                                `<button onclick="confirmDelete('${row.id}')" class="btn btn-danger"><i class="fa-regular fa-trash"></i></button>`;
                            return `<div class="d-flex flex-row gap-2">${showBtn}${editBtn}${deleteBtn}</div>`;
                        }
                    }
                ],
                dom: "<'row'<'col-12 col-sm-3'l><'col-  12 col-sm-9 text-end text-sm-start'f>>" +
                    "<'row dt-row'<'col-12'tr>>" +
                    "<'row'<'col-12 col-sm-4 text-center text-sm-start'i><'col-12 col-sm-8 text-center text-sm-end'p>>",
                "language": {
                    "info": "Page _PAGE_ of _PAGES_",
                    "lengthMenu": "_MENU_ ",
                }
            });

            FilePond.create(document.querySelector(".basic-filepond"), {
                credits: null,
                allowImagePreview: false,
                allowMultiple: false,
                allowFileEncode: false,
                required: true,
                storeAsFile: true,
            })
        });
    </script>
    <script>
        const show = (id) => {
            $.getJSON(`${window.location.origin}/admin/guru/data/${id}`, (data) => {
                $('#show-username').val(data.username);
                $('#show-password').val(data.password);

                const myModal = new bootstrap.Modal(document.getElementById('showGuruModal'));
                myModal.show();
            })
        }

        const edit = (id) => {
            $.getJSON(`${window.location.origin}/admin/guru/data/${id}`, (data) => {
                const updateUrl = "{{ route('admin.guru.edit', ':id') }}"

                $('#form-edit-guru').attr('action', updateUrl.replace(':id', id));
                $('#edit-nama-guru').val(data.nama_lengkap);
                $('#edit-nomor-wa-guru').val(data.guru.nomor_wa);

                const myModal = new bootstrap.Modal(document.getElementById('editGuruModal'));
                myModal.show();
            })
        }

        const confirmDelete = (id) => {
            const deleteUrl = "{{ route('admin.guru.delete', ':id') }}"

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
                    Swal.fire("Success, Guru berhasil dihapus!", {
                        icon: "success",
                    });
                } else {
                    Swal.fire("Penghapusan guru dibatalkan!");
                }
            });

        }
    </script>
@endpush
