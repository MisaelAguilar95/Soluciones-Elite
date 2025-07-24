{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Soluciones Elite')

@section('content')
<main class="main-content mt-0">
  <!-- Hero Header -->
  <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg"
       style="background-image: url('https://raw.githubusercontent.com/creativetimofficial/public-assets/master/argon-dashboard-pro/assets/img/signup-cover.jpg'); background-position: top;">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5 text-center mx-auto">
          <h1 class="text-white mb-2 mt-5">Soluciones Elite!</h1>
          <p class="text-lead text-white">Crédito fácil, rápido y a tu medida.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- login Card -->
  <div class="container">
    <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
      <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
        <div class="card z-index-0">
          <div class="card-header text-center pt-4">
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
</main>
@endsection
