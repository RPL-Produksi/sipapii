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
        @if (auth()->user()->role == 'admin')
            @include('template.sidebar-menu.sidebar-admin')
        @elseif (auth()->user()->role == 'guru')
            @include('template.sidebar-menu.sidebar-guru')
        @endif
    </div>
</div>
