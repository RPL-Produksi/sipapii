@extends('layouts.app-2')

@php
    if (Request::query('type') == 'masuk') {
        $status = 'Masuk';
    } elseif (Request::query('type') == 'pulang') {
        $status = 'Pulang';
        $absenId = Request::query('absen_id');
    } else {
        $status = '';
    }
@endphp

@section('title', 'Absen ' . $status)

@push('css')
    {{-- CSS Only For This Page --}}
    <link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
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

        .webcam,
        .webcam video {
            display: inline-block;
            width: 100% !important;
            height: auto !important;
            margin: auto;
            text-align: center;
            border-radius: 15px;
            overflow: hidden;
        }

        .rating-star {
            font-size: 28px;
            cursor: pointer;
            position: relative;
            transition: transform 0.2s;
        }

        .rating-star:hover {
            transform: scale(1.2);
        }

        .fa-star-half-stroke,
        .fa-solid.fa-star {
            color: gold;
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
                <div class="divider-text">Lat-Long: <span id="latLong"></span></div>
            </div>

            <div class="row">
                @include('template.feedback')
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <form action="{{ route('siswa.absen.post', ['type' => strtolower($status), 'absen_id' => @$absenId]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card border">
                            <div class="card-body text-center">

                                <!-- Kamera Desktop #m1 -->
                                <div class="d-inline-block text-center">
                                    <div id="camera" class="d-none mb-3"></div>
                                </div>

                                <!-- Dump Image #m1 -->
                                <img id="dumpImage" src="{{ asset('assets/static/images/camera.png') }}"
                                    class="img-fluid mb-3">
                                <!-- Preview Container #m1 -->
                                <div id="preview" class="d-none">
                                    <img id="capturedImage" class="img-fluid">
                                </div>
                                <div class="form-group">
                                    @if (Request::query('type') == 'masuk')
                                        <select name="status" id="selectKeterangan" class="form-control choices" required>
                                            <option value="Hadir">Hadir</option>
                                            <option value="Izin">Izin</option>
                                            <option value="Sakit">Sakit</option>
                                        </select>
                                    @endif
                                    <!-- Hidden File Input #m1 -->
                                    <input type="file" accept="image/*" capture="camera" name="camera_data"
                                        id="cameraInput" class="d-none">
                                    <input type="hidden" name="lat" id="latitude" value="">
                                    <input type="hidden" name="long" id="longitude" value="">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-success d-block w-100" id="takePicture">
                                    <i class="fa-regular fa-camera me-2"></i>Ambil Gambar
                                </button>

                                <div class="row d-none" id="btnGroupAbsen">
                                    <div class="col-9 col-md-10">
                                        <button type="{{ Request::query('type') == 'masuk' ? 'submit' : 'button' }}"
                                            class="btn btn-success w-100" id="submitAbsen"
                                            @if (Request::query('type') == 'pulang') data-bs-toggle="modal" 
                                                data-bs-target="#absenPulangModal" @endif
                                            data-loading="true">
                                            <i class="fa-regular fa-camera me-2"></i>Absen {{ $status }}
                                        </button>
                                    </div>
                                    <div class="col-3 col-md-2">
                                        <button type="button" class="btn btn-warning w-100" id="retakePicture">
                                            <i class="fa-regular fa-rotate"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-bottom: 7rem;"></div>

    <div class="modal fade" id="absenPulangModal" tabindex="-1" role="dialog" aria-labelledby="absenPulangModalTitle"
        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="absenPulangModalTitle">Data Absensi Siswa Hari Ini</h5>
                </div>
                <form method="POST" id="absenPulangForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="selectKeterangan">Keterangan</label>
                            <select name="status" id="selectKeterangan" class="form-control choices" required>
                                <option value="Hadir">Hadir</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                            </select>
                        </div>
                        <div class="form-group" id="alasanGroup" style="display: none;">
                            <label for="alasan">Alasan</label>
                            <input type="text" id="alasan" name="alasan" class="form-control"
                                placeholder="Masukan alasan (Wajib)">
                        </div>
                        <div class="form-group">
                            <label for="jurnal">Jurnal</label>
                            <textarea name="jurnal" id="jurnal" rows="3" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Rating Tugas Yang Telah Dikerjakan</label>
                            <div id="ratingTugasStars" class="rating-star-wrapper"></div>
                            <input type="hidden" name="rating_tugas" id="ratingTugasValue" required>
                        </div>

                        <div class="form-group">
                            <label>Rating Kompetensi Yang Ada Dijurusan (Sesuai/Tidak)</label>
                            <div id="ratingKompetensiStars" class="rating-star-wrapper"></div>
                            <input type="hidden" name="rating_kompetensi" id="ratingKompetensiValue" required>
                        </div>
                        <div class="form-group">
                            <input type="file" accept="image/*" capture="camera" name="camera_data"
                                id="cameraInputPulang" class="d-none">
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn" data-bs-dismiss="modal">
                            <span>Tutup</span>
                        </button> --}}
                        <button type="submit" class="btn btn-primary" data-loading="true">
                            <span>Absen</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- JS Only For This Page --}}
    <script src="{{ asset('assets/extensions/webcamjs/webcamjs.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script src="{{ asset('assets/static/js/helper/cam.js') }}"></script>
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
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const long = position.coords.longitude;

                    console.log(lat, long);

                    $('#latitude').val(lat);
                    $('#longitude').val(long);

                    $('#latLong').text(`${lat}, ${long}`);

                    Toastify({
                        text: "Lokasi anda telah terdektesi",
                        duration: 3000,
                        close: true,
                        backgroundColor: "#198754",
                    }).showToast()
                },
                function(error) {
                    Toastify({
                        text: "Gagal mendeteksi lokasi anda",
                        duration: 3000,
                        close: true,
                        backgroundColor: "#dc3545",
                    }).showToast()
                }
            );
        } else {
            Toastify({
                text: "Geolocation tidak didukung pada browser anda",
                duration: 3000,
                close: true,
                backgroundColor: "#ffc107",
            }).showToast()
        }
    </script>
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

            $('#selectKeterangan').on('change', function() {
                if ($(this).val() === 'Izin' || $(this).val() === 'Sakit') {
                    $('#alasanGroup').show();
                } else {
                    $('#alasanGroup').hide();
                }
            });
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
        $('#submitAbsen').click(() => {
            const keterangan = $('#selectKeterangan').val();
            const alasan = $('#alasan').val();
            const jurnal = $('#jurnal').val();
            $('#absenPulangForm').attr('action',
                `{!! route('siswa.absen.post', ['type' => 'pulang', 'absen_id' => @$absenId]) !!}`);


            const cameraData = $('#capturedImage').attr('src');
            const imageFile = dataURItoFile(cameraData, 'snapshot.jpg');
            const fileInput = document.getElementById("cameraInputPulang");
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(imageFile);
            fileInput.files = dataTransfer.files;

            if (keterangan === 'Izin' || keterangan === 'Sakit') {
                if (alasan === '') {
                    Toastify({
                        text: "Alasan tidak boleh kosong",
                        duration: 3000,
                        close: true,
                        backgroundColor: "#dc3545",
                    }).showToast()
                    return;
                }
            }

            if (jurnal === '' && new URLSearchParams(window.location.search).get('type') === 'pulang') {
                Toastify({
                    text: "Jurnal tidak boleh kosong",
                    duration: 3000,
                    close: true,
                    backgroundColor: "#dc3545",
                }).showToast();
                return;
            }
        })

        const dataURItoFile = (dataURI, fileName) => {
            const byteString = atob(dataURI.split(",")[1]);
            const mimeString = dataURI.split(",")[0].split(":")[1].split(";")[0];
            const ab = new ArrayBuffer(byteString.length);
            const ia = new Uint8Array(ab);
            for (let i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new File([ab], fileName, {
                type: mimeString
            });
        }
    </script>
    <script>
        function initRating(containerId, inputId) {
            const container = document.getElementById(containerId);
            const input = document.getElementById(inputId);
            for (let i = 1; i <= 5; i++) {
                const star = document.createElement('i');
                star.classList.add('fa-regular', 'fa-star', 'rating-star');
                star.dataset.value = i;
                container.appendChild(star);
            }

            let selectedRating = 0;

            container.querySelectorAll(".rating-star").forEach((star, index) => {
                star.addEventListener("mousemove", function(e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const half = x < rect.width / 2;
                    highlightStars(container, index + 1 - (half ? 0.5 : 0));
                });

                star.addEventListener("click", function(e) {
                    const rect = this.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const half = x < rect.width / 2;
                    selectedRating = index + 1 - (half ? 0.5 : 0);
                    input.value = selectedRating * 20;
                });

                star.addEventListener("mouseleave", () => {
                    highlightStars(container, selectedRating);
                });
            });

            function highlightStars(container, rating) {
                const stars = container.querySelectorAll(".rating-star");
                stars.forEach((star, i) => {
                    star.classList.remove("fa-solid", "fa-regular", "fa-star-half-stroke");
                    if (rating >= i + 1) {
                        star.classList.add("fa-solid", "fa-star");
                    } else if (rating >= i + 0.5) {
                        star.classList.add("fa-solid", "fa-star-half-stroke");
                    } else {
                        star.classList.add("fa-regular", "fa-star");
                    }
                });
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            initRating('ratingTugasStars', 'ratingTugasValue');
            initRating('ratingKompetensiStars', 'ratingKompetensiValue');
        });
    </script>
@endpush
