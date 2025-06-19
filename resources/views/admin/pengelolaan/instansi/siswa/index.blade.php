@extends('layouts.app')
@section('title', 'Kelola Instansi - Siswa')

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
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Siswa | {{ $instansi->nama }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered nowrap w-100" id="table-1">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Guru Pembimbing</th>
                                <th>Guru Mapel PKL</th>
                                <th class="text-center">Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($siswa as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->user->nama_lengkap }}</td>
                                    <td>{{ $item->kelas->nama }}</td>
                                    <td>{{ $item->pembimbingan->pembimbing->user->nama_lengkap ?? '-' }}</td>
                                    <td>{{ $item->pembimbingan->guruMapelPKL->user->nama_lengkap ?? '-' }}</td>
                                    <td>
                                        <div class="badge bg-{{ $item->user->is_active ? 'success' : 'danger' }} d-block">
                                            {{ $item->user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($item->user->is_active)
                                            <button class="btn btn-danger"
                                                onclick="confirmNonactive('{{ $instansi->id }}', '{{ $item->id }}')"><i
                                                    class="fa-regular fa-bolt"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
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
        const confirmNonactive = (id, siswaId) => {
            const nonactiveUrl = "{{ route('admin.pengelolaan.instansi.siswa.nonactive', [':id', ':siswaId']) }}"

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengaktifkan siswa yang dinonaktifkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, nonaktifkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = nonactiveUrl.replace(':id', id).replace(":siswaId", siswaId);
                    Swal.fire("Success, Siswa berhasil dinonaktifkan!", {
                        icon: "success",
                    });
                } else {
                    Swal.fire("Pengnonaktifkan siswa dibatalkan!");
                }
            });
        }
    </script>
@endpush
