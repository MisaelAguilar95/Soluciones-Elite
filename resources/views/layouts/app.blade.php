<!DOCTYPE html>
<html lang="en">
<style>
  /* Sidebar oculto por defecto en móvil */
  @media (max-width: 1199.98px) {
    #sidenav-main {
      transform: translateX(-100%);
      transition: transform 0.3s ease;
      z-index: 1055;
    }

    #sidenav-main.active {
      transform: translateX(0);
      box-shadow: 2px 0 15px rgba(0, 0, 0, 0.3);
    }
  }
</style>

<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Soluciones Elite')</title>
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.1.0') }}" rel="stylesheet" />
</head>

<body class="">

  {{-- Main Content --}}
  @yield('content')

  {{-- Scripts --}}
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), { damping: '0.5' });
    }
  </script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="{{ asset('assets/js/argon-dashboard.min.js?v=2.1.0') }}"></script>

  {{-- Sidebar toggle script --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebar = document.getElementById('sidenav-main');
      const openBtn = document.getElementById('toggleSidebar');
      const closeBtn = document.getElementById('iconSidenav'); // normalmente el botón "X" del sidebar

      openBtn?.addEventListener('click', () => {
        sidebar.classList.add('active');
        openBtn.classList.add('d-none'); // Ocultar el botón hamburguesa
      });

      closeBtn?.addEventListener('click', () => {
        sidebar.classList.remove('active');
        openBtn.classList.remove('d-none'); // Mostrar el botón hamburguesa
      });

      // Cierra el sidebar si haces clic fuera en móvil
      document.addEventListener('click', function (e) {
        if (
          window.innerWidth < 1200 &&
          sidebar.classList.contains('active') &&
          !sidebar.contains(e.target) &&
          !openBtn.contains(e.target)
        ) {
          sidebar.classList.remove('active');
          openBtn.classList.remove('d-none');
        }
      });
    });
  </script>
</body>
</html>
