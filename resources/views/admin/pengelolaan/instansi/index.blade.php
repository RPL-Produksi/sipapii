@extends('layouts.app')
@section('title', 'Kelola Instansi')

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
                        <h4 class="card-title">Daftar Instansi</h4>
                        <div class="float-right">
                            <a href="{{ route('admin.pengelolaan.instansi.form') }}" class="btn btn-success"><i
                                    class="fa-regular fa-add"></i></a>
                            <a href="{{ route('admin.pengelolaan.instansi.export') }}" class="btn btn-danger"><i
                                    class="fa-regular fa-file-export text-white"></i></a>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importInstansiModal"><i
                                    class="fa-regular fa-file-import text-white"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered nowrap w-100" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Domisili</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="importInstansiModal" tabindex="-1" role="dialog"
        aria-labelledby="importInstansiModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importInstansiModalTitle">Import Data Instansi</h5>
                </div>
                <form action="{{ route('admin.pengelolaan.instansi.import') }}" method="POST"
                    enctype="multipart/form-data">
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
                                    <p class="text-danger">*Domisili Wajib (Luar Kota/Dalam Kota)</p>
                                    <p class="text-secondary">Donwload contoh di sebelah -> </p>
                                </div>
                                <a href="{{ asset('assets/import/contoh_format_import_instansi.xlsx') }}"
                                    class="btn btn-danger float-right mb-2"><i class="fa-regular fa-download"></i></a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">no</th>
                                            <th>nama</th>
                                            <th>alamat</th>
                                            <th>domisili</th>
                                            <th>latitude</th>
                                            <th>longitude</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>PT. Jerbee Indonesia</td>
                                            <td>Jl. Suryalaya Timur IV No.20, Cijagra, Kec. Lengkong, Kota Bandung</td>
                                            <td>Luar Kota</td>
                                            <td>-6.94610824364657</td>
                                            <td>107.62596100280193</td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">...</td>
                                            <td>...</td>
                                            <td>...</td>
                                            <td>...</td>
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
                        <button type="submit" class="btn btn-success ms-1" data-loading="true">
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
                    url: "{{ route('admin.pengelolaan.instansi.data') }}",
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
                        data: 'nama',
                        orderable: true,
                    },
                    {
                        data: 'alamat',
                        orderable: true,
                    },
                    {
                        data: 'domisili',
                        orderable: true,
                    },
                    {
                        data: 'latitude',
                        orderable: false,
                    },
                    {
                        data: 'longitude',
                        orderable: false,
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            let editUrl = "{{ route('admin.pengelolaan.instansi.form', ':id') }}";
                            editUrl = editUrl.replace(':id', row.id);

                            let instansiSiswaUrl =
                                "{{ route('admin.pengelolaan.instansi.siswa', ':id') }}"
                            instansiSiswaUrl = instansiSiswaUrl.replace(":id", row.id)

                            let instansiSiswaBtn =
                                `<a href="${instansiSiswaUrl}" class="btn btn-success"><i class="fa-regular fa-eye"></i></a>`
                            let editBtn =
                                `<a href="${editUrl}" class="btn btn-primary"><i class="fa-regular fa-edit"></i></a>`;
                            let deleteBtn =
                                `<button onclick="confirmDelete('${row.id}')" class="btn btn-danger"><i class="fa-regular fa-trash"></i></button>`;
                            return `<div class="d-flex flex-row gap-2">${instansiSiswaBtn}${editBtn}${deleteBtn}</div>`;
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

        FilePond.create(document.querySelector(".basic-filepond"), {
            credits: null,
            allowImagePreview: false,
            allowMultiple: false,
            allowFileEncode: false,
            required: true,
            storeAsFile: true,
        })
    </script>
    <script>
        const confirmDelete = (id) => {
            const deleteUrl = "{{ route('admin.pengelolaan.instansi.delete', ':id') }}"

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
                    Swal.fire("Success, Kelas berhasil dihapus!", {
                        icon: "success",
                    });
                } else {
                    Swal.fire("Penghapusan kelas dibatalkan!");
                }
            });

        }
    </script>
@endpush
