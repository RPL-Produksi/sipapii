@extends('layouts.app')
@section('title', 'Form Instansi')

@push('css')
    {{-- CSS Only For This Page --}}
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/leaflet/leaflet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css') }}">
@endpush

@php
    $defaultLat = -6.93484106751055;
    $defaultLon = 106.92595802590458;
@endphp

@section('content')
    <section class="row">
        @include('template.feedback')

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $instansi ? 'Edit Instansi | ' . $instansi->nama : 'Tambah Instansi' }}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form action="{{ route('admin.pengelolaan.instansi.form.store', @$instansi->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="nama-instansi">Nama Instansi</label>
                                        <input type="text" id="nama-instansi" class="form-control"
                                            placeholder="Masukan Nama Instansi" name="nama"
                                            value="{{ old('nama', @$instansi->nama) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <input type="text" id="alamat" class="form-control"
                                            placeholder="Masukan Alamat" name="alamat"
                                            value="{{ old('alamat', @$instansi->alamat) }}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Domisili</label>
                                        <select class="choices form-select" name="domisili" required>
                                            <option value="Dalam Kota" @selected(old('domisili', @$instansi->domisili) == 'Dalam Kota')>Dalam Kota</option>
                                            <option value="Luar Kota" @selected(old('domisili', @$instansi->domisili) == 'Luar Kota')>Luar Kota</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <label for="latitude">Latitude</label>
                                        <input type="text" id="latitude" class="form-control" placeholder=""
                                            name="lat"
                                            value="{{ old('latitude', @$instansi->latitude ?: $defaultLat) }}"
                                            id="latitude" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="longitude">Longitude</label>
                                        <input type="text" id="longitude" class="form-control" placeholder=""
                                            name="long"
                                            value="{{ old('longitude', @$instansi->longitude ?: $defaultLon) }}"
                                            id="longitude" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div id="map" style="height: 400px; margin-bottom: 20px; margin-top: 50px;"></div>
                                </div>
                                <div class="col-12 d-flex justify-content-end">
                                    <a href="{{ route('admin.pengelolaan.instansi') }}" type="button"
                                        class="btn  me-1 mb-1">Batal</a>
                                    <button type="submit"
                                        class="btn btn-primary me-1 mb-1">{{ $instansi ? 'Ubah' : 'Tambah' }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('js')
    {{-- JS Only For This Page --}}
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script src="{{ asset('assets/extensions/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('assets/extensions/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js') }}"></script>
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
        var defaultLat = -6.934838404922678;
        var defaultLon = 106.92595936700863;

        $(document).ready(function() {
            var lat = $('#latitude').val() || defaultLat;
            var lon = $('#longitude').val() || defaultLon;

            var map = L.map('map').setView([lat, lon], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            var marker = L.marker([lat, lon], {
                draggable: true
            }).addTo(map);

            marker.on('dragend', function() {
                var position = marker.getLatLng();
                updateInputFields(position.lat, position.lng);
            });

            map.on('click', function(event) {
                var lat = event.latlng.lat;
                var lon = event.latlng.lng;

                marker.setLatLng([lat, lon]);
                updateInputFields(lat, lon);
            });

            function updateInputFields(lat, lon) {
                $('#latitude').val(lat);
                $('#longitude').val(lon);
            }

            $('#latitude').on('input', function() {
                var lat = $('#latitude').val();
                var lon = $('#longitude').val();

                if (!isNaN(lat) && !isNaN(lon)) {
                    marker.setLatLng([lat, lon]);
                    map.setView([lat, lon], 13);
                }
            });

            $('#longitude').on('input', function() {
                var lat = $('#latitude').val();
                var lon = $('#longitude').val();

                if (!isNaN(lat) && !isNaN(lon)) {
                    marker.setLatLng([lat, lon]);
                    map.setView([lat, lon], 13);
                }
            });
        });
    </script>
@endpush
