@php
    use App\Models\Absen;
    use Carbon\Carbon;

    $siswa = Auth::user();
    $absen_url = null;

    if ($siswa) {
        $absenHariIni = Absen::where('siswa_id', $siswa->siswa->id)
            ->where('tanggal', Carbon::now()->format('d-m-Y'))
            ->first();

        if (!$absenHariIni || !$absenHariIni->jam_masuk) {
            $absen_url = route('siswa.absen', ['type' => 'masuk']);
        } elseif (!$absenHariIni->jam_pulang) {
            $absen_url = route('siswa.absen', ['type' => 'pulang', 'absen_id' => $absenHariIni->id]);
        } else {
            $absen_url = null;
        }
    }
@endphp

<div class="w-100 bottom-0 left-0 bg-body right-0 d-flex align-items-center justify-content-center shadow-lg border-top border-1 
border-primary"
    style="position: fixed; height: 65px; z-index: 100;">
    <a href="{{ route('siswa.dashboard') }}"
        class="row d-flex align-items-center justify-content-center text-center w-100 {{ @$menu_type == 'dashboard' ? 'nav-link' : '' }}"
        style="height: 45px">
        <div class="col">
            <i class="fa-regular fa-home fs-4"></i>
            <p class="fw-bold">Beranda</p>
        </div>
    </a>
    <a href="{{ route('siswa.jurnal') }}"
        class="row d-flex align-items-center justify-content-center text-center w-100 {{ @$menu_type == 'jurnal' ? 'nav-link' : '' }}"
        style="height: 45px">
        <div class="col">
            <i class="fa-regular fa-book fs-4"></i>
            <p class="fw-bold">Jurnal</p>
        </div>
    </a>
    <a href="{{ $absen_url }}" class="row d-flex align-items-center justify-content-center text-center w-100 {{ @$menu_type == 'absen' ? 'nav-link' : '' }}"
        style="height: 45px">
        <div class="col">
            <i class="fa-regular fa-camera fs-4"></i>
            <p class="fw-bold">Absen</p>
        </div>
    </a>
    <a href="{{ route('siswa.riwayat') }}"
        class="row d-flex align-items-center justify-content-center text-center w-100 {{ @$menu_type == 'riwayat' ? 'nav-link' : '' }}"
        style="height: 45px">
        <div class="col">
            <i class="fa-regular fa-clock-rotate-left fs-4"></i>
            <p class="fw-bold">Riwayat</p>
        </div>
    </a>
    <a href="{{ route('siswa.profile') }}"
        class="row d-flex align-items-center justify-content-center text-center w-100 {{ @$menu_type == 'profile' ? 'nav-link' : '' }}"
        style="height: 45px">
        <div class="col">
            <i class="fa-regular fa-user fs-4"></i>
            <p class="fw-bold">Profil</p>
        </div>
    </a>
</div>
