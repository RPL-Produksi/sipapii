@extends('layouts.app-2')
@section('title', 'Siswa Dashboard')

@push('css')
    {{-- CSS Only For This Page --}}
    <style>
        .capsule {
            position: relative;
        }

        .capsule::before {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            content: "";
            display: block;
            height: 140px;
            background: #435ebe;
        }
    </style>
@endpush

@section('content')
    <div class="capsule pt-1 px-3">
        <div class="card p-4 shadow-lg">
            <div class="row">
                <div class="col-6">
                    <p id="greetings">Selamat Siang</p>
                    <h2 id="namaLengkap">{{ $siswa->nama_lengkap }}</h2>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                    <div class="text-end">
                        <p id="currentDate">Senin, 21 November 2024</p>
                        <h3 id="currentTime">10:23:10</h3>
                    </div>
                </div>
            </div>

            <div class="divider">
                <div class="divider-text">Status Absensi</div>
            </div>

            <div class="row">
                @include('template.feedback')

                <div class="col-12 col-md-6 mt-2">
                    <a href="{{ route('siswa.absen', ['type' => 'masuk']) }}"
                        class="btn btn-success d-block w-100 rounded-5 {{ @$absen->jam_masuk ? 'disabled' : '' }}">
                        <div class="d-block">
                            <h5 class="p-0 m-0 text-white">Absen Masuk</h5>
                            <p class="p-0 m-0">{{ @$absen->jam_masuk ?: 'Belum Absen' }}</p>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-6 mt-2">
                    <a href="{{ route('siswa.absen', ['type' => 'pulang', 'absen_id' => @$absen->id]) }}"
                        class="btn btn-danger d-block w-100 rounded-5 {{ @$absen->jam_pulang ? 'disabled' : '' }}">
                        <div class="d-block">
                            <h5 class="p-0 m-0 text-white">Absen Pulang</h5>
                            <p class="p-0 m-0">{{ @$absen->jam_pulang ?: 'Belum Absen' }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4">
        <div class="mt-3">
            <h5>Rekap Absensi</h5>
        </div>
        <div class="row">
            <div class="col-6 col-md-3">
                <a href="">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-start">
                                <i class="fa-regular fa-right-to-bracket fs-3 text-success"></i>
                                <div class="">
                                    <strong class="ms-3">Hadir</strong>
                                    <p class="ms-3 mb-0">{{ $status_counts['Hadir'] }} Hari</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-start">
                                <i class="fa-regular fa-flag-swallowtail fs-3 text-primary"></i>
                                <div class="">
                                    <strong class="ms-3">Izin</strong>
                                    <p class="ms-3 mb-0">{{ $status_counts['Izin'] }} Hari</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-start">
                                <i class="fa-regular fa-face-thermometer fs-3 text-warning"></i>
                                <div class="">
                                    <strong class="ms-3">Sakit</strong>
                                    <p class="ms-3 mb-0">{{ $status_counts['Sakit'] }} Hari</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-start">
                                <i class="fa-regular fa-circle-exclamation fs-3 text-danger"></i>
                                <div class="">
                                    <strong class="ms-3">Alpa</strong>
                                    <p class="ms-3 mb-0">{{ $status_counts['Alpa'] }} Hari</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <div class="px-4">
        <div class="mt-3">
            <h5>Data Jurnal</h5>
        </div>
        <div class="row">
            @if ($jurnal->isEmpty())
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-center">Belum ada data jurnal</p>
                        </div>
                    </div>
                </div>
            @else
                @foreach ($jurnal as $item)
                    <div class="col-12 col-md-6 col-lg-4 mt-3">
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
            @endif
        </div>
    </div>

    <div style="margin-bottom: 5rem;"></div>

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
    <script>
        $(document).ready(function() {
            const namaLengkap = $('#namaLengkap');
            const maxLength = 15;

            if (namaLengkap.length) {
                const fullName = namaLengkap.text().trim();

                if (fullName.length > maxLength) {
                    const shortenedName = fullName.slice(0, maxLength) + '...';
                    namaLengkap.text(shortenedName);
                }
            }

            const jam = new Date().getHours();
            let ucapan = "Selamat Siang";
            if (jam >= 5 && jam < 11) {
                ucapan = "Selamat Pagi";
            } else if (jam >= 11 && jam < 15) {
                ucapan = "Selamat Siang";
            } else if (jam >= 15 && jam < 18) {
                ucapan = "Selamat Sore";
            } else {
                ucapan = "Selamat Malam";
            }

            $("#greetings").text(ucapan);
        });
    </script>
    <script>
        const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
        const months = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        function updateClock() {
            const now = new Date();

            const dayName = days[now.getDay()];
            const date = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();

            const hours = String(now.getHours()).padStart(2, "0");
            const minutes = String(now.getMinutes()).padStart(2, "0");
            const seconds = String(now.getSeconds()).padStart(2, "0");

            $("#currentDate").text(`${dayName}, ${date} ${monthName} ${year}`);
            $("#currentTime").text(`${hours}:${minutes}:${seconds}`);
        }

        setInterval(updateClock, 1000);
        updateClock();
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
