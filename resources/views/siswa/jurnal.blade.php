@extends('layouts.app-2')
@section('title', 'Jurnal Siswa')

@push('css')
    {{-- CSS Only For This Page --}}
    <link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css') }}">
@endpush

@section('content')
    <div class="px-4 py-4">
        <div class="row">
            @include('template.feedback')

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('siswa.jurnal') }}">
                            <div class="row">
                                <div class="col-6">
                                    <label for="tanggalAwal">Tanggal Awal</label>
                                    <input type="date" name="tanggal_awal" id="tanggalAwal" class="form-control"
                                        value="{{ request('tanggal_awal') }}">
                                </div>
                                <div class="col-6">
                                    <label for="tanggalAkhir">Tanggal Akhir</label>
                                    <input type="date" name="tanggal_akhir" id="tanggalAkhir" class="form-control"
                                        value="{{ request('tanggal_akhir') }}">
                                </div>
                                <div class="col-12 mt-2">
                                    <button class="btn btn-primary w-100" type="submit">
                                        <i class="fa-regular fa-filter me-2"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <h4>Data Jurnal</h4>
                <div class="row" id="jurnal-container">
                    @foreach ($jurnal as $item)
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h4 class="card-title">{{ $item->tanggal }}</h4>
                                </div>
                                <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                                    <p class="card-text">
                                        @if ($item->deskripsi_jurnal)
                                            {{ $item->deskripsi_jurnal }}
                                        @else
                                            <span class="d-flex align-items-center justify-content-center">Tidak membuat
                                                jurnal</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="card-text">
                                            Status:
                                            @if ($item->validasi == 'Divalidasi')
                                                <span class="badge bg-success">Divalidasi</span>
                                            @elseif ($item->validasi == 'Belum Divalidasi')
                                                <span class="badge bg-warning">Belum Divalidasi</span>
                                            @elseif ($item->validasi == 'Ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Mengisi</span>
                                            @endif
                                        </p>
                                        <div class="">
                                            @if ($item->validasi == 'Belum Divalidasi' || $item->validasi == 'Ditolak')
                                                <button class="btn btn-primary btn-sm"
                                                    onclick="editJurnal('{{ $item->id }}')">Ubah Jurnal</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $jurnal->links() }}
                </div>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 7rem;"></div>

    <div class="modal fade" id="editJurnalModal" tabindex="-1" role="dialog" aria-labelledby="editJurnalModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editJurnalModalTitle">Edit Jurnal</h5>
                </div>
                <form action="" method="POST" id="editJurnalForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Deskripsi Jurnal</label>
                            <textarea name="deskripsi_jurnal" class="form-control" id="deskripsiJurnal" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">
                            <span>Batal</span>
                        </button>
                        <button type="submit" class="btn btn-primary">
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
    <script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script>
        $(document).on('click', '.pagination a, #btnFilter', function(e) {
            e.preventDefault();

            let url = $(this).attr('href') || '{{ route('siswa.jurnal') }}';
            let tanggalAwal = $('#tanggalAwal').val();
            let tanggalAkhir = $('#tanggalAkhir').val();

            $.ajax({
                url: url,
                data: {
                    tanggal_awal: tanggalAwal,
                    tanggal_akhir: tanggalAkhir,
                },
                success: function(response) {
                    $('#jurnal-container').html($(response).find('#jurnal-container').html());
                }
            });
        });
    </script>
    <script>
        const editJurnal = (id) => {
            $.getJSON(`${window.location.origin}/siswa/jurnal/data/${id}`, (data) => {
                const updateUrl = '{{ route('siswa.jurnal.edit', ':id') }}'
                $('#editJurnalForm').attr('action', updateUrl.replace(':id', id));


                $('#deskripsiJurnal').val(data.deskripsi_jurnal);

                const myModal = new bootstrap.Modal(document.getElementById('editJurnalModal'));
                myModal.show();
            })
        }
    </script>
@endpush
