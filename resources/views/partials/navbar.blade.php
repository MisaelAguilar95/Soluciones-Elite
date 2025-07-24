<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
  <!-- Botón solo visible en móvil -->
<button id="toggleSidebar" class="btn btn-primary d-xl-none me-3 mt-2">
  <i class="fa-solid fa-bars"></i>
</button>
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
     
    </nav>
    <div class="collapse navbar-collapse " id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <div class="input-group">
         
        </div>
      </div>
      <ul class="navbar-nav justify-content-end">
        <li class="nav-item d-flex align-items-center">
          
          <a href="{{ route('logout') }}" class="nav-link text-white font-weight-bold px-0"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fa fa-user me-sm-1"></i>
              <span class="d-sm-inline d-none">Cerrar Sesión</span>
            </a>
          </li>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>

        </ul>
    </div>
  </div>
</nav>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js"></script>


