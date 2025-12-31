@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="card">
    <div class="card-header">
      <h4>Editar Usuario</h4>
    </div>

    <div class="card-body">
      <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Rol</label>
          <select name="level" class="form-select">
            <option value="user" {{ $user->level === 'user' ? 'selected' : '' }}>Usuario</option>
            <option value="admin" {{ $user->level === 'admin' ? 'selected' : '' }}>Administrador</option>
          </select>
        </div>

        <button class="btn btn-primary">Guardar cambios</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
      </form>
    </div>
  </div>
</div>
@endsection
