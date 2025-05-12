@extends('layouts.app')
@section('title', 'Data Nilai PKL Siswa')

@push('css')
    {{-- CSS Only For This Page --}}
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/dataTables.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
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
                                <h4 class="card-title">Nilai PKL Siswa</h4>
                                <div class="float-right">
                                    <button class="btn btn-success" data-bs-target="#storeNilaiModal"
                                        data-bs-toggle="modal">
                                        <div class="fa-regular fa-plus"></div>
                                    </button>
                                    <button class="btn btn-danger">
                                        <div class="fa-regular fa-file-excel"></div>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered w-100 nowrap" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Internalisasi dan Penerapan Soft Skills</th>
                                        <th>Penerapan Hard Skills</th>
                                        <th>Peningkatan dan Pengembangan Hard Skills</th>
                                        <th>Penyiapan Kemandirian Berwirausaha</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($nilai as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->siswa->user->nama_lengkap }}</td>
                                            <td>{{ $item->siswa->kelas->nama }}</td>
                                            <td>{{ $item->nilai1 }}</td>
                                            <td>{{ $item->nilai2 }}</td>
                                            <td>{{ $item->nilai3 }}</td>
                                            <td>{{ $item->nilai4 }}</td>
                                            <td>
                                                <button onclick="show('{{ $item->id }}')"
                                                    class="btn btn-warning text-white">
                                                    <i class="fa-regular fa-eye"></i>
                                                </button>
                                                <button onclick="edit('{{ $item->id }}')" class="btn btn-primary">
                                                    <i class="fa-regular fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="storeNilaiModal" tabindex="-1" role="dialog" aria-labelledby="storeNilaiModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="storeNilaiModalTitle">Tambah Nilai PKL</h5>
                </div>
                <form action="{{ route('guru.siswa.nilai.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="">Nama Siswa</label>
                                <select class="choices form-select" id="siswa" name="siswa_id">
                                    <option value="" selected disabled>Pilih Siswa</option>
                                    @foreach ($siswa as $item)
                                        <option value="{{ $item->id }}">{{ $item->user->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label for="">Internalisasi dan Penerapan Soft Skills</label>
                                <input type="text" inputmode="numeric" class="form-control" name="nilai1"
                                    placeholder="Masukkan Nilai">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="">Penerapan Hard Skills</label>
                                <input type="text" inputmode="numeric" class="form-control" name="nilai2"
                                    placeholder="Masukkan Nilai">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="">Peningkatan dan Pengembangan Hard Skills</label>
                                <input type="text" inputmode="numeric" class="form-control" name="nilai3"
                                    placeholder="Masukkan Nilai">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="">Penyiapan Kemandirian Berwirausaha</label>
                                <input type="text" inputmode="numeric" class="form-control" name="nilai4"
                                    placeholder="Masukkan Nilai">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link text-decoration-none" data-bs-dismiss="modal">
                            <span>Tutup</span>
                        </button>
                        <button type="submit" class="btn btn-success">
                            <span>Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editNilaiModal" tabindex="-1" role="dialog" aria-labelledby="editNilaiModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNilaiModalTitle">Edit Nilai PKL</h5>
                </div>
                <form action="" method="POST" id="formEditNilai">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="">Nama Siswa</label>
                                <input type="text" id="editSiswaNama" class="form-control" disabled>
                                <input type="hidden" name="siswa_id" id="editSiswaId" class="form-control">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="">Internalisasi dan Penerapan Soft Skills</label>
                                <input type="text" inputmode="numeric" class="form-control" name="nilai1"
                                    placeholder="Masukkan Nilai" id="editNilai1">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="">Penerapan Hard Skills</label>
                                <input type="text" inputmode="numeric" class="form-control" name="nilai2"
                                    placeholder="Masukkan Nilai" id="editNilai2">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="">Peningkatan dan Pengembangan Hard Skills</label>
                                <input type="text" inputmode="numeric" class="form-control" name="nilai3"
                                    placeholder="Masukkan Nilai" id="editNilai3">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="">Penyiapan Kemandirian Berwirausaha</label>
                                <input type="text" inputmode="numeric" class="form-control" name="nilai4"
                                    placeholder="Masukkan Nilai" id="editNilai4">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link text-decoration-none" data-bs-dismiss="modal">
                            <span>Tutup</span>
                        </button>
                        <button type="submit" class="btn btn-success">
                            <span>Simpan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailNilaiModal" tabindex="-1" role="dialog" aria-labelledby="detailNilaiModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailNilaiModalTitle">Detail Nilai PKL</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <h6>Nama Siswa</h4>
                        </div>
                        <div class="col-sm-8">
                            <p id="detailNamaLengkap"></p>
                        </div>
                        <div class="col-sm-4 mb-4">
                            <h6>Kelas</h4>
                        </div>
                        <div class="col-sm-8 mb-4">
                            <p id="detailKelas"></p>
                        </div>
                        <div class="col-sm-8">
                            <h6>Mata Pelajaran</h6>
                        </div>
                        <div class="col-sm-4">
                            <h6>Nilai</h6>
                        </div>
                        <hr>
                        <div class="col-sm-8">
                            <h6>Internalisasi dan Penerapan Soft Skills</h6>
                        </div>
                        <div class="col-sm-4">
                            <p id="detailNilai1">0</p>
                        </div>
                        <div class="col-sm-8">
                            <h6>Penerapan Hard Skills</h6>
                        </div>
                        <div class="col-sm-4">
                            <p id="detailNilai2">0</p>
                        </div>
                        <div class="col-sm-8">
                            <h6>Peningkatan dan Pengembangan Hard Skills</h6>
                        </div>
                        <div class="col-sm-4">
                            <p id="detailNilai3">0</p>
                        </div>
                        <div class="col-sm-8">
                            <h6>Penyiapan Kemandirian Berwirausaha</h6>
                        </div>
                        <div class="col-sm-4">
                            <p id="detailNilai4">0</p>
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
@endsection

@push('js')
    {{-- JS Only For This Page --}}
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
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
            $('#table-1').DataTable({
                responsive: true,
                scrollX: true,
            });
        });
    </script>
    <script>
        const show = (id) => {
            $.getJSON(`${window.location.origin}/guru/siswa/nilai/data/${id}`, (data) => {
                $('#detailNamaLengkap').text(data.siswa.user.nama_lengkap)
                $('#detailKelas').text(data.siswa.kelas.nama)
                $('#detailNilai1').text(data.nilai1)
                $('#detailNilai2').text(data.nilai2)
                $('#detailNilai3').text(data.nilai3)
                $('#detailNilai4').text(data.nilai4)

                const myModal = new bootstrap.Modal(document.getElementById('detailNilaiModal'))
                myModal.show()
            })
        }

        const edit = (id) => {
            $.getJSON(`${window.location.origin}/guru/siswa/nilai/data/${id}`, (data) => {
                const updateUrl = '{{ route('guru.siswa.nilai.store', ':id') }}'
                $('#formEditNilai').attr('action', updateUrl.replace(':id', id))

                $('#editSiswaId').val(data.siswa.id)
                $('#editSiswaNama').val(data.siswa.user.nama_lengkap)
                $('#editNilai1').val(data.nilai1)
                $('#editNilai2').val(data.nilai2)
                $('#editNilai3').val(data.nilai3)
                $('#editNilai4').val(data.nilai4)

                const myModal = new bootstrap.Modal(document.getElementById('editNilaiModal'));
                myModal.show();
            })
        }
    </script>
@endpush
