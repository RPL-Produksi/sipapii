<div id="sidebar">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <h3>{{ env('APP_NAME') }}</h3>
                </div>

                <div class="sidebar-toggler x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Dashboard</li>

                <li class="sidebar-item {{ @$menu_type == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                        <i class="fa-regular fa-grid-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="sidebar-title">Pengelolaan</li>

                <li class="sidebar-item {{ @$menu_type == 'pengelolaan-instansi' ? 'active' : '' }}">
                    <a href="{{ route('admin.pengelolaan.instansi') }}" class="sidebar-link">
                        <i class="fa-regular fa-building"></i>
                        <span>Kelola Instansi</span>
                    </a>
                </li>
                
                <li class="sidebar-item {{ @$menu_type == 'penempatan' ? 'active' : '' }}">
                    <a href="{{ route('admin.pengelolaan.penempatan') }}" class="sidebar-link">
                        <i class="fa-regular fa-location-dot"></i>
                        <span>Kelola Penempatan</span>
                    </a>
                </li>

                <li class="sidebar-item {{ @$menu_type == 'pembimbingan' ? 'active' : '' }}">
                    <a href="{{ route('admin.pengelolaan.pembimbingan') }}" class="sidebar-link">
                        <i class="fa-regular fa-screen-users"></i>
                        <span>Kelola Pembimbingan</span>
                    </a>
                </li>

                <li class="sidebar-item {{ @$menu_type == 'pengelolaan-kelas' ? 'active' : '' }}">
                    <a href="{{ route('admin.pengelolaan.kelas') }}" class="sidebar-link">
                        <i class="fa-regular fa-school"></i>
                        <span>Kelola Kelas</span>
                    </a>
                </li>


                <li class="sidebar-item {{ @$menu_type == 'pengelolaan-tahun-ajar' ? 'active' : '' }}">
                    <a href="{{ route('admin.pengelolaan.tahun-ajar') }}" class="sidebar-link">
                        <i class="fa-regular fa-calendar-days"></i>
                        <span>Kelola Tahun Ajar</span>
                    </a>
                </li>

                <li class="sidebar-title">Data</li>

                <li class="sidebar-item has-sub {{ @$menu_type == 'siswa' ? 'active' : '' }}">
                    <a href="#" class="sidebar-link">
                        <i class="fa-regular fa-graduation-cap"></i>
                        <span>Siswa</span>
                    </a>

                    <ul class="submenu">
                        <li class="submenu-item {{ @$submenu_type == 'siswa-data' ? 'active' : '' }}">
                            <a href="{{ route('admin.siswa') }}" class="submenu-link">Data Siswa</a>
                        </li>

                        <li class="submenu-item">
                            <a href="table-datatable-jquery.html" class="submenu-link">Nilai Siswa</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item {{ @$menu_type == 'guru-mapel-pembimbing' ? 'active' : '' }}">
                    <a href="{{ route('admin.guru') }}" class="sidebar-link">
                        <i class="fa-regular fa-chalkboard-user"></i>
                        <span>Guru Mapel PKL & Pembimbing</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
