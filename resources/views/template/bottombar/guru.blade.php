<div class="w-100 bottom-0 left-0 bg-body right-0 d-flex align-items-center justify-content-center shadow-lg border-top border-1 border-primary"
    style="position: fixed; height: 65px; z-index: 100;">

    <a href="{{ route('guru.dashboard') }}"
        class="row d-flex align-items-center justify-content-center text-center w-100 {{ @$menu_type == 'dashboard' ? 'nav-link' : '' }}"
        style="height: 45px">
        <div class="col">
            <i class="fa-regular fa-grid-2 fs-4"></i>
            <p class="fw-bold">Dashboard</p>
        </div>
    </a>

    <div class="dropup text-center w-100">
        <a href="#"
            class="row d-flex align-items-center justify-content-center text-center w-100 {{ @$menu_type == 'absen' ? 'nav-link' : '' }}"
            style="height: 45px" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="col">
                <i class="fa-regular fa-check-to-slot fs-4"></i>
                <p class="fw-bold">Absensi Siswa</p>
            </div>
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item {{ @$submenu_type == 'absensi' ? 'active' : '' }}" href="{{ route('guru.siswa.absen', ['type' => 'data']) }}">Data Absensi</a></li>
            <li><a class="dropdown-item {{ @$submenu_type == 'rekap' ? 'active' : '' }}" href="{{ route('guru.siswa.absen', ['type' => 'rekap']) }}">Rekap Absensi</a></li>
        </ul>
    </div>


    <a href="{{ route('guru.siswa.jurnal') }}"
        class="row d-flex align-items-center justify-content-center text-center w-100 {{ @$menu_type == 'jurnal' ? 'nav-link' : '' }}"
        style="height: 45px">
        <div class="col">
            <i class="fa-regular fa-book-journal-whills fs-4"></i>
            <p class="fw-bold">Jurnal</p>
        </div>
    </a>

    <a href="{{ route('guru.siswa.nilai') }}"
        class="row d-flex align-items-center justify-content-center text-center w-100 {{ @$menu_type == 'nilai-siswa' ? 'nav-link' : '' }}"
        style="height: 45px">
        <div class="col">
            <i class="fa-regular fa-book-sparkles fs-4"></i>
            <p class="fw-bold">Nilai Siswa</p>
        </div>
    </a>

    <a href="{{ route('guru.siswa') }}"
        class="row d-flex align-items-center justify-content-center text-center w-100 {{ @$menu_type == 'siswa-data' ? 'nav-link' : '' }}"
        style="height: 45px">
        <div class="col">
            <i class="fa-regular fa-users fs-4"></i>
            <p class="fw-bold">Siswa</p>
        </div>
    </a>
</div>
