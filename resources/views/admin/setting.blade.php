@extends('layouts.app')
@section('title', 'Setting')

@push('css')
    {{-- CSS Only For This Page --}}
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/dataTables.bootstrap5.min.css') }}"
        rel="stylesheet">
    <link href="{{ asset('assets/extensions/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet">
@endpush

@section('content')
    <section class="row">
        @include('template.feedback')

        <div class="d-flex justify-content-center align-items-center" style="height: 75vh;">
            <div class="text-center">
                <img src="{{ asset('assets/static/images/undraw_maintenance_rjtm.svg') }}" alt="Under Development"
                    style="max-width: 300px;">
                <h1 class="mt-4">Halaman Sedang Dikembangkan</h1>
                <p class="text-muted">Fitur ini belum tersedia saat ini. Silakan kembali nanti.</p>
                <a href="{{ url()->previous() }}" class="btn btn-primary mt-3">
                    <i class="fa-regular fa-arrow-left me-1"></i> Kembali
                </a>
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
    <script>
        $(document).ready(function() {
            $('#table-1').DataTable({
                responsive: true,
                scrollX: true,
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
@endpush
