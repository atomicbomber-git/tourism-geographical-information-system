<nav class="navbar navbar-dark bg-dark navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#"> {{ config('app.name') }} </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <li class='nav-item {{ Route::is('waypoint.*') ? 'active' : '' }}'>
                    <a class='nav-link' href='{{ route('waypoint.index') }}'>
                        <i class='fa fa-road'></i>
                        Waypoint
                    </a>
                </li>

                <li class='nav-item {{ Route::is('site.*') ? 'active' : '' }}'>
                    <a class='nav-link' href='{{ route('site.map') }}'>
                        <i class='fa fa-tree'></i>
                        Situs
                    </a>
                </li>
            </div>
        </div>
    </div>
</nav>