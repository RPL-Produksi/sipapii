@extends('layouts.app')
@section('title', 'Kelola Pembimbingan')

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
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Pembimbingan</h4>
                        <div class="float-right">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPembimbinganModal"><i
                                    class="fa-regular fa-add"></i></button>
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
                                <th>Guru Pembimbing</th>
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

    <div class="modal fade" id="addPembimbinganModal" tabindex="-1" role="dialog"
        aria-labelledby="addPembimbinganModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPembimbinganModalTitle">Tambah Pembimbingan dan Guru Mapel PKL</h5>
                </div>
                <form action="{{ route('admin.pengelolaan.pembimbingan.add') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Daftar Guru Pembimbing</label>
                                    <select class="choices form-select" name="pembimbing_id">
                                        <option value="" disabled selected>Pilih Guru Pembimbing</option>
                                        @foreach ($guru as $item)
                                            <option value="{{ $item->id }}">{{ $item->user->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Daftar Guru Mapel PKL</label>
                                    <select class="choices form-select" name="guru_mapel_pkl_id">
                                        <option value="" disabled selected>Pilih Guru Mapel PKL</option>
                                        @foreach ($guru as $item)
                                            <option value="{{ $item->id }}">{{ $item->user->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Daftar Siswa</label>
                                    <select class="choices form-select multiple-remove" multiple="multiple"
                                        name="siswa_id[]">
                                        <option value="" disabled>Pilih Siswa</option>
                                        @foreach ($siswa as $item)
                                            <option value="{{ $item->id }}">{{ $item->user->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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

    <div class="modal fade" id="editPembimbinganModal" tabindex="-1" role="dialog" aria-labelledby="editPembimbinganModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPembimbinganModal">Edit Pembimbingan dan Guru Mapel PKL</h5>
                </div>
                <form method="POST" id="editPembimbinganForm">
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Nama Siswa</label>
                                    <input type="text" disabled class="form-control" id="editNamaSiswa" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Data Guru Pembimbing</label>
                                    <select id="editPembimbing" class="form-select" name="pembimbing_id">
                                        <option value="" disabled selected>Pilih Guru Pembimbing</option>
                                        @foreach ($guru as $item)
                                            <option value="{{ $item->id }}">{{ $item->user->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Data Guru Mapel PKL</label>
                                    <select id="editGuruMapelPkl" class="form-select" name="guru_mapel_pkl_id">
                                        <option value="" disabled selected>Pilih Guru Mapel PKL</option>
                                        @foreach ($guru as $item)
                                            <option value="{{ $item->id }}">{{ $item->user->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                    url: "{{ route('admin.pengelolaan.pembimbingan.data') }}",
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
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'siswa.user.nama_lengkap',
                        orderable: true,
                    },
                    {
                        data: 'siswa.penempatan.instansi.nama',
                        orderable: false,
                    },
                    {
                        data: 'pembimbing.user.nama_lengkap',
                        orderable: false,
                    },
                    {
                        data: 'guru_mapel_pkl.user.nama_lengkap',
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
        let editPembimbingChoices;
        let editGuruMapelPklChoices;

        $(document).ready(() => {
            editPembimbingChoices = new Choices("#editPembimbing", {
                searchEnabled: true,
                removeItemButton: true,
            });

            editGuruMapelPklChoices = new Choices("#editGuruMapelPkl", {
                searchEnabled: true,
                removeItemButton: true,
            });
        })

        const edit = (id) => {
            $.getJSON(`${window.location.origin}/admin/pengelolaan/pembimbingan/data/${id}`, (data) => {
                const updateUrl = `{{ route('admin.pengelolaan.pembimbingan.edit', ':id') }}`

                $('#editPembimbinganForm').attr('action', updateUrl.replace(':id', id))
                $('#editNamaSiswa').val(data.siswa.user.nama_lengkap);
                editPembimbingChoices.setChoiceByValue(data.pembimbing_id);
                editGuruMapelPklChoices.setChoiceByValue(data.guru_mapel_pkl_id);

                const myModal = new bootstrap.Modal(document.getElementById('editPembimbinganModal'));
                myModal.show();
            })
        }

        const confirmDelete = (id) => {
            const deleteUrl = "{{ route('admin.pengelolaan.pembimbingan.delete', ':id') }}"

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
                    Swal.fire("Success, Pembimbingan berhasil dihapus!", {
                        icon: "success",
                    });
                } else {
                    Swal.fire("Penghapusan pembimbingan dibatalkan!");
                }
            });
        }
    </script>
@endpush
