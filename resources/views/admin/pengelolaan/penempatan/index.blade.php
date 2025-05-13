@extends('layouts.app')
@section('title', 'Kelola Penempatan')

@push('css')
    {{-- CSS Only For This Page --}}
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/dataTables.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">
@endpush

@section('content')
    <section class="row">
        @include('template.feedback')

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Penempatan</h4>
                        <div class="float-right">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPenempatanModal"><i
                                    class="fa-regular fa-add"></i></button>
                            <button class="btn btn-warning text-white" data-bs-toggle="modal"
                                data-bs-target="#importPenempatanModal">
                                <i class="fa-regular fa-file-import"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered nowrap w-100" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama Siswa</th>
                                <th>Nama Instansi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="addPenempatanModal" tabindex="-1" role="dialog" aria-labelledby="addPenempatanModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPenempatanModalTitle">Buat Penempatan</h5>
                </div>
                <form action="{{ route('admin.pengelolaan.penempatan.add') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="">Daftar Siswa</label>
                            <select class="choices form-select multiple-remove" multiple="multiple" name="siswa_id[]">
                                <option value="" disabled>Pilih Siswa</option>
                                @foreach ($siswa as $item)
                                    <option value="{{ $item->id }}">{{ $item->user->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Daftar Instansi</label>
                            <select class="choices form-select" name="instansi_id" required>
                                <option value="" disabled>Pilih Instansi</option>
                                @foreach ($instansi as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">
                            <span>Batal</span>
                        </button>
                        <button type="submit" class="btn btn-success ms-1">
                            <span>Buat</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPenempatanModal" tabindex="-1" role="dialog" aria-labelledby="editPenempatanModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPenempatanModal">Edit Penempatan</h5>
                </div>
                <form method="POST" id="editPenempatanForm">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="">Nama Siswa</label>
                            <input type="text" disabled class="form-control" id="editNamaSiswa" required>
                        </div>
                        <div class="form-group">
                            <label for="">Daftar Instansi</label>
                            <select id="editInstansi" class="form-select" name="instansi_id" required>
                                <option value="" disabled>Pilih Instansi</option>
                                @foreach ($instansi as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
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

    <div class="modal fade" id="importPenempatanModal" tabindex="-1" role="dialog"
        aria-labelledby="importPenempatanModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-full modal-dialog-centered modal-dialog-centered modal-dialog-scrollable"
            role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importPenempatanModalTitle">Import Data Penempatan</h5>
                </div>
                <form action="{{ route('admin.pengelolaan.penempatan.import') }}" method="POST"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="mb-2">File CSV | Excel</label>
                            <input type="file" name="file" id="file" class="basic-filepond"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                required />
                        </div>
                        <div class="form-group">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-block">
                                    <label for="table">Contoh Table</label>
                                    <span class="text-danger">*Header jangan di hapus</span>
                                    <p class="text-danger">*Domisili Wajib (Luar Kota/Dalam Kota)</p>
                                    <p class="text-secondary">Donwload contoh di sebelah -></p>
                                </div>
                                <a href="{{ asset('assets/import/contoh_format_import_penempatan.xlsx') }}"
                                    class="btn btn-danger float-right mb-2"><i class="fa-regular fa-download"></i></a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center">no</th>
                                            <th>nis</th>
                                            <th>nama_siswa</th>
                                            <th>kelas</th>
                                            <th>jenis_kelamin</th>
                                            <th>tahun_ajar</th>
                                            <th>nama_instansi</th>
                                            <th>domisili</th>
                                            <th>alamat</th>
                                            <th>latitude</th>
                                            <th>longitude</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>12209322</td>
                                            <td>Muhamad Hilal</td>
                                            <td>12 RPL 1</td>
                                            <td>L</td>
                                            <td>2024/2025</td>
                                            <td>PT. Jerbee Indonesia</td>
                                            <td>Luar Kota</td>
                                            <td>Jl. Suryalaya Timur IV No.20, Cijagra, Kec. Lengkong, Kota Bandung</td>
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
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script src="{{ asset('assets/extensions/filepond/filepond.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#table-1').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                scrollX: true,
                ajax: {
                    url: "{{ route('admin.pengelolaan.penempatan.data') }}",
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
                        data: 'siswa.user.nama_lengkap',
                        orderable: true,
                    },
                    {
                        data: 'instansi.nama',
                        orderable: true,
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
        let editInstansiChoices;

        $(document).ready(() => {
            editInstansiChoices = new Choices("#editInstansi", {
                searchEnabled: true,
                removeItemButton: true,
            });
        })

        const edit = (id) => {
            $.getJSON(`${window.location.origin}/admin/pengelolaan/penempatan/data/${id}`, (data) => {
                const updateUrl = `{{ route('admin.pengelolaan.penempatan.edit', ':id') }}`

                $('#editPenempatanForm').attr('action', updateUrl.replace(':id', id))
                $('#editNamaSiswa').val(data.siswa.user.nama_lengkap);
                editInstansiChoices.setChoiceByValue(data.instansi_id);

                const myModal = new bootstrap.Modal(document.getElementById('editPenempatanModal'));
                myModal.show();
            })
        }

        const confirmDelete = (id) => {
            const deleteUrl = "{{ route('admin.pengelolaan.penempatan.delete', ':id') }}"

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
                    Swal.fire("Success, Penempatan berhasil dihapus!", {
                        icon: "success",
                    });
                } else {
                    Swal.fire("Penghapusan penempatan dibatalkan!");
                }
            });
        }
    </script>
@endpush
