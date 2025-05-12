<div class="sidebar-menu">
    <ul class="menu">
        <li class="sidebar-title">Dashboard</li>

        <li class="sidebar-item {{ @$menu_type == 'dashboard' ? 'active' : '' }}">
            <a href="{{ route('guru.dashboard') }}" class="sidebar-link">
                <i class="fa-regular fa-grid-2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="sidebar-title">Data</li>

        <li class="sidebar-item has-sub {{ @$menu_type == 'absen' ? 'active' : '' }}">
            <a href="#" class="sidebar-link">
                <i class="fa-regular fa-check-to-slot"></i>
                <span>Absensi Siswa</span>
            </a>

            <ul class="submenu">
                <li class="submenu-item {{ @$submenu_type == 'absensi' ? 'active' : '' }}">
                    <a href="{{ route('guru.siswa.absen', ['type' => 'data']) }}" class="submenu-link">Data
                        Absensi</a>
                </li>

                <li class="submenu-item {{ @$submenu_type == 'rekap' ? 'active' : '' }}">
                    <a href="{{ route('guru.siswa.absen', ['type' => 'rekap']) }}" class="submenu-link">Rekap
                        Absensi</a>
                </li>
            </ul>
        </li>

        <li class="sidebar-item {{ @$menu_type == 'jurnal' ? 'active' : '' }}">
            <a href="{{ route('guru.siswa.jurnal') }}" class="sidebar-link">
                <i class="fa-regular fa-book-journal-whills"></i>
                <span>Jurnal</span>
            </a>
        </li>

        <li class="sidebar-item {{ @$menu_type == 'nilai-siswa' ? 'active' : '' }}">
            <a href="{{ route('guru.siswa.nilai') }}" class="sidebar-link">
                <i class="fa-regular fa-book-sparkles"></i>
                <span>Nilai Siswa</span>
            </a>
        </li>

        <li class="sidebar-item {{ @$menu_type == 'siswa' ? 'active' : '' }}">
            <a href="{{ route('guru.dashboard') }}" class="sidebar-link">
                <i class="fa-regular fa-users"></i>
                <span>Siswa</span>
            </a>
        </li>
    </ul>
</div>
