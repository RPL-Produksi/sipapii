@extends('layouts.app')
@section('title', 'Kelola Kelas')

@push('css')
    {{-- CSS Only For This Page --}}
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/dataTables.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
    <section class="row">
        @include('template.feedback')

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Kelas - {{ $tahunAjar->tahun_ajar }}</h4>
                        <div class="float-right">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addKelasModal"><i
                                    class="fa-regular fa-add"></i></button>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#importKelasModal"><i
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

    <div class="modal fade" id="addKelasModal" tabindex="-1" role="dialog" aria-labelledby="addKelasModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKelasModalTitle">Tambah Kelas</h5>
                </div>
                <form action="{{ route('admin.pengelolaan.tahun-ajar.kelas.add', $tahunAjar->id) }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" id="nama" name="nama"
                                placeholder="Masukan Nama Kelas" required>
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

    <div class="modal fade" id="editKelasModal" tabindex="-1" role="dialog" aria-labelledby="editKelasModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKelasModalTitle">Edit Kelas</h5>
                </div>
                <form method="POST" id="editKelasForm">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" id="edit-nama-kelas" name="nama"
                                placeholder="Masukan Nama Kelas" required>
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

    <div class="modal fade" id="importKelasModal" tabindex="-1" role="dialog" aria-labelledby="importKelasModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importKelasModalTitle">Import Data Kelas</h5>
                </div>
                <form action="{{ route('admin.pengelolaan.tahun-ajar.kelas.import', $tahunAjar->id) }}" method="POST"
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
                                    <p class="text-secondary">Donwload contoh di sebelah -></p>
                                </div>
                                <a href="{{ asset('assets/import/contoh_format_import_kelas.xlsx') }}"
                                    class="btn btn-danger float-right mb-2"><i class="fa-regular fa-download"></i></a>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">no</th>
                                        <th>nama</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>10 AKL 1</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">...</td>
                                        <td>...</td>
                                    </tr>
                                </tbody>
                            </table>
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
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
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
        $(document).ready(function() {
            let url = '{{ route('admin.pengelolaan.tahun-ajar.kelas.data', ':id') }}'

            $('#table-1').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: url.replace(':id', '{{ $tahunAjar->id }}'),
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
                        data: 'tahun_ajar.tahun_ajar',
                        orderable: false,
                    },
                    {
                        data: 'id',
                        orderable: false,
                        render: function(data, type, row, meta) {
                            let editBtn =
                                `<button onclick='edit("${data}")' class="btn btn-primary"><i class="fa-regular fa-edit"></i></button>`;
                            let deleteBtn =
                                `<button onclick="confirmDelete('${row.id}')" class="btn btn-danger"><i class="fa-regular fa-trash"></i></button>`;
                            return `<div class="d-flex flex-row gap-2">${editBtn}${deleteBtn}</div>`;
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
        const edit = (id) => {
            $.getJSON(`${window.location.origin}/admin/pengelolaan/tahun-ajar/{{ $tahunAjar->id }}/kelas/data/${id}`, (
                data) => {
                const updateUrl = `{{ route('admin.pengelolaan.tahun-ajar.kelas.edit', [':id', ':kelasId']) }}`

                $('#editKelasForm').attr('action', updateUrl.replace(':id', '{{ $tahunAjar->id }}').replace(
                    ':kelasId', id))
                $('#edit-nama-kelas').val(data.nama);

                const myModal = new bootstrap.Modal(document.getElementById('editKelasModal'));
                myModal.show();
            })
        }

        const confirmDelete = (id) => {
            const deleteUrl = "{{ route('admin.pengelolaan.tahun-ajar.kelas.delete', [':id', ':kelasId']) }}"

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengembalikan data yang sudah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl.replace(':id', '{{ $tahunAjar->id }}').replace(':kelasId',
                        id);
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
