@extends('layouts.app')
@section('title', 'Form Siswa')

@push('css')
    {{-- CSS Only For This Page --}}
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush

@section('content')
    <section class="row">
        @include('template.feedback')

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $siswa ? 'Edit Siswa | ' . $siswa->user->nama_lengkap : 'Tambah Siswa' }}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form action="{{ route('admin.siswa.form.store', @$siswa->user->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="nama-lengkap">Nama Lengkap</label>
                                        <input type="text" id="nama-lengkap" class="form-control"
                                            placeholder="Masukan Nama Lengkap" name="nama_lengkap"
                                            value="{{ old('nama_lengkap', @$siswa->user->nama_lengkap) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="nis">NIS</label>
                                        <input type="text" id="nis" class="form-control" placeholder="Masukan Nis"
                                            name="nis" value="{{ old('nis', @$siswa->nis) }}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <select class="choices form-select" name="jenis_kelamin" id="jenis_kelamin"
                                            required>
                                            <option value="" @selected(old('kelas_id', @$siswa->jenis_kelamin) == '') disabled>Pilih Jenis Kelamin
                                            </option>
                                            <option value="Laki-laki" @selected(old('jenis_kelamin', @$siswa->jenis_kelamin) == 'Laki-laki')>Laki-laki</option>
                                            <option value="Perempuan" @selected(old('jenis_kelamin', @$siswa->jenis_kelamin) == 'Perempuan')>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="ol-12">
                                    <div class="form-group">
                                        <label for="kelas_id">Kelas</label>
                                        <select class="choices form-select" name="kelas_id" id="kelas_id" required>
                                            <option value="" @selected(old('kelas_id', @$siswa->kelas_id) == '') disabled>Pilih Kelas
                                            </option>
                                            @foreach ($kelas as $item)
                                                <option value="{{ $item->id }}" @selected(old('kelas_id', @$siswa->kelas_id) == $item->id)>
                                                    {{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end mt-3">
                                    <a href="{{ route('admin.siswa') }}" type="button" class="btn  me-1 mb-1">Batal</a>
                                    <button type="submit"
                                        class="btn btn-primary me-1 mb-1">{{ $siswa ? 'Ubah' : 'Tambah' }}</button>
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
@endpush
