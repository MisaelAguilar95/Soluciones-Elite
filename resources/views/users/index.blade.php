@extends('layouts.app')

@section('content')
<div class="min-height-300 bg-dark position-absolute w-100"></div>
@include('partials.sidebar')

<main class="main-content position-relative px-3">
  @include('partials.navbar')

  <div class="container-fluid py-4">
    <div class="card">
      <div class="card-body bg-transparent">
        <div class="d-flex justify-content-between mb-3">
          <h3><i class="ni ni-single-02 text-dark opacity-10"></i> Usuarios</h3>
          <a href="{{ route('users.create') }}" class="btn btn-primary">+ Nuevo Usuario</a>
        </div>

        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Creado</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge bg-{{ $user->level === 'admin' ? 'danger' : 'secondary' }}">{{ ucfirst($user->level) }}</span></td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{ $users->links() }}
      </div>
    </div>
  </div>

  @include('partials.footer')
</main>
@endsection
