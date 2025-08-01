<nav class="navbar sticky-top navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{route('dashboard')}}">User Permission</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">User Permission</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
            @foreach ($routes as $route)
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs($route->route) ? 'active' : '' }}" href="{{ route($route->route) }}">
                        {{ $route->title }}
                    </a>
                </li>
            @endforeach
        </ul>
        <form action="{{route('logout')}}" class="d-flex mt-3" method="POST">
            @csrf
          <button class="btn btn-outline-danger" type="submit">Logout</button>
        </form>
      </div>
    </div>
  </div>
</nav>
