<nav class="navbar navbar-dark bg-dark navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}"> {{ config('app.name') }} </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">

            <div class="navbar-nav">
                @auth

                <li class='nav-item dropdown {{ Route::is('slide.*') ? 'active' : '' }}'>
                    <a
                        class='nav-link dropdown-toggle' href='#' id='slide' role='button'
                        data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fa fa-wrench'></i>
                        Pengaturan Web
                    </a>
                    <div class='dropdown-menu' aria-labelledby='slide'>
                        <a class='dropdown-item' href='{{ route('slide.index') }}'> Gambar Slide </a>
                    </div>
                </li>

                <li class='nav-item dropdown {{ Route::is('waypoint.*') ? 'active' : '' }}'>
                    <a
                        class='nav-link dropdown-toggle' href='#' id='waypoint' role='button'
                        data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fa fa-road'></i>
                        Waypoint
                    </a>
                    <div class='dropdown-menu' aria-labelledby='waypoint'>
                        <a class='dropdown-item' href='{{ route('waypoint.index') }}'> Daftar Waypoint </a>
                        <a class='dropdown-item' href='{{ route('waypoint.create') }}'> Tambah Waypoint </a>
                    </div>
                </li>

                <li class='nav-item dropdown {{ Route::is('path.*') ? 'active' : '' }}'>
                    <a
                        class='nav-link dropdown-toggle' href='#' id='path' role='button'
                        data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fa fa-arrow-circle-o-right'></i>
                        Jalur
                    </a>
                    <div class='dropdown-menu' aria-labelledby='path'>
                        <a class='dropdown-item' href='{{ route('path.index') }}'> Daftar Jalur </a>
                        <a class='dropdown-item' href='{{ route('path.create') }}'> Tambah Jalur </a>
                    </div>
                </li>

                <li class='nav-item dropdown {{ Route::is('site.*') ? 'active' : '' }}'>
                    <a
                        class='nav-link dropdown-toggle' href='#' id='site' role='button'
                        data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fa fa-tree'></i>
                        Situs
                    </a>
                    <div class='dropdown-menu' aria-labelledby='site'>
                        <a class='dropdown-item' href='{{ route('site.index') }}'> Daftar Situs </a>
                        <a class='dropdown-item' href='{{ route('site.create') }}'> Tambah Situs </a>
                        <a class='dropdown-item' href='{{ route('site.map') }}'> Peta Rute </a>
                        <a class='dropdown-item' href='{{ route('site.analyze') }}'> Situs Terfavorit </a>
                    </div>
                </li>

                <li class='nav-item dropdown {{ Route::is('site-category.*') ? 'active' : '' }}'>
                    <a
                        class='nav-link dropdown-toggle' href='#' id='site_category' role='button'
                        data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fa fa-list'></i>
                        Kategori Situs
                    </a>
                    <div class='dropdown-menu' aria-labelledby='site_category'>
                        <a class='dropdown-item' href='{{ route('site-category.index') }}'> Daftar Kategori </a>
                        <a class='dropdown-item' href='{{ route('site-category.create') }}'> Tambah Kategori </a>
                    </div>
                </li>

                @endauth
            </div>

            <div class="navbar-nav ml-auto">
                @auth
                <form class="mr-auto" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger">
                        Keluar
                        <i class="fa fa-sign-out"></i>
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </div>
</nav>