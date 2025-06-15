@extends('layouts.app-3')
@section('title', 'Data Siswa')

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
                            <h4 class="card-title">Daftar Siswa</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered w-100 nowrap" id="table-1">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Instansi</th>
                                        <th>Pembimbing</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->user->nama_lengkap }}</td>
                                            <td>{{ $item->kelas->nama }}</td>
                                            <td>{{ $item->penempatan->instansi->nama }}</td>
                                            <td>{{ $item->pembimbingan->pembimbing->user->nama_lengkap }}</td>
                                            <td>
                                                <button onclick="show('{{ $item->id }}')"
                                                    class="btn btn-warning text-white">
                                                    <i class="fa-regular fa-eye"></i>
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

    <div class="modal fade" id="detailSiswaModal" tabindex="-1" role="dialog" aria-labelledby="detailSiswaModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailSiswaModalTitle">Detail Siswa</h5>
                </div>
                <div class="modal-body">
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
                            <h6>Instansi</h4>
                        </div>
                        <div class="col-sm-8">
                            <p id="detailInstansi"></p>
                        </div>
                        <div class="col-sm-4">
                            <h6>Pembimbing</h4>
                        </div>
                        <div class="col-sm-8">
                            <p id="detaiPembimbing"></p>
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
            $.getJSON(`${window.location.origin}/guru/siswa/data/${id}`, (data) => {
                $('#detailNamaLengkap').text(data.user.nama_lengkap)
                $('#detailKelas').text(data.kelas.nama)
                $('#detailInstansi').text(data.penempatan.instansi.nama)
                $('#detaiPembimbing').text(data.pembimbingan.pembimbing.user.nama_lengkap)

                const myModal = new bootstrap.Modal(document.getElementById('detailSiswaModal'))
                myModal.show()
            })
        }
    </script>
@endpush
