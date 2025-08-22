{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Soluciones El Lic')

@section('content')
<main class="main-content mt-0">
  <!-- Hero Header con imagen de fondo -->
  <div class="page-header min-vh-100 d-flex align-items-center justify-content-center"
       style="background-image: url('{{ asset('assets/img/login2.jpg') }}'); background-size: cover; background-position: center;">

    <!-- Máscara oscura -->
    <span class="mask bg-gradient-dark opacity-6 position-absolute top-0 start-0 w-100 h-100"></span>

    <!-- Contenido centrado -->
    <div class="container position-relative z-index-2">
      <div class="row justify-content-center">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">

          <!-- Título -->
          <div class="text-center text-white mb-4">
            <h1 class="text-white">Soluciones El Lic!</h1>
            <h5 class="text-white">-Crédito fácil, rápido y a tu medida-</h5>
          </div>

          <!-- Tarjeta de login -->
        <div class="card z-index-2" style="background-color: rgba(255, 255, 255, 0.45);">
            <div class="card-header text-center pt-4" style="background-color: rgba(255, 255, 255, 0.75);">
              <h5>Inicio de Sesión</h5>
            </div>
            <div class="card-body">
              <form method="POST" action="{{ route('login') }}">
                @csrf
                @if ($errors->has('email'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>😓 Error:</strong> {{ $errors->first('email') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                @endif
                <div class="mb-3">
                  <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Ingresar</button>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</main>
@endsection
