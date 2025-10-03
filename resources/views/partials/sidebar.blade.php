<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="#">
      <img src="{{ asset('assets/img/logo.png') }}" width="36px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
      <span class="ms-1 font-weight-bold">&nbsp;&nbsp;&nbsp;Soluciones El Lic</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <di v class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Prestamo</h6>
        </li>
        <li class="nav-item">
         <a class="nav-link {{ request()->routeIs('clients.index') ? 'active' : '' }}" href="{{ route('clients.index') }}">
           <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
             <i class="ni ni-satisfied text-dark text-sm opacity-10"></i>
           </div>
           <span class="nav-link-text ms-1">Clientes</span>
         </a>
        </li>
         <li class="nav-item">
         <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-chart-pie-35 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Administración</h6>
        </li>
        {{-- <li class="nav-item">
         <a class="nav-link {{ request()->routeIs('reporte-prestamos') ? 'active' : '' }}" href="{{ route('reporte.prestamos') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-chart-bar-32 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Reporte General</span>
          </a>
        </li> --}}
         <li class="nav-item">
         <a class="nav-link {{ request()->routeIs('reportes.index') ? 'active' : '' }}" href="{{ route('reportes.index') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-chart-bar-32 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Reporte Por Prestamista</span>
          </a>
        </li>
          @if(auth()->user()->level === 'admin')
        <li class="nav-item">
         <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <i class="ni ni-single-02 text-dark"></i>
            <span class="nav-link-text ms-1">Usuarios</span>
          </a>
        </li>
        @endif
        <li>
           <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-user me-sm-1 text-dark"></i>
            <span class="nav-link-text ms-1">Cerrar Sesión</span>
          </a>
        </li>
      </ul>
  </div>
</aside>
