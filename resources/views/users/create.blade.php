@extends('layouts.app')

@section('content')
<div class="min-height-300 bg-dark position-absolute w-100"></div>
@include('partials.sidebar')

<main class="main-content position-relative px-3">
  @include('partials.navbar')

  <div class="container-fluid py-4">
    <div class="card">
      <div class="card-body">
        <h3 class="mb-4">Crear Usuario</h3>

        <form method="POST" action="{{ route('users.store') }}" autocomplete="off">
          @csrf

          <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Correo</label>
            <input type="email" name="email" class="form-control" autocomplete="off" required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" autocomplete="off" required>
          </div>

          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="level" class="form-label">Rol</label>
            <select name="level" class="form-select" required>
              <option value="user">Usuario</option>
              <option value="admin">Administrador</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Crear</button>
        </form>
      </div>
    </div>
  </div>

  @include('partials.footer')
</main>
@endsection
