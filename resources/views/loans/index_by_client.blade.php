@extends('layouts.app')

@section('content')
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  @include('partials.sidebar')

  <main class="main-content position-relative px-3">
    @include('partials.navbar')

    <div class="container-fluid py-4">
      <div class="row ">
        <div class="col-lg-12 mb-lg-0 mb-4">
          <div class="card">
            <div class="card-body pb-0 pt-3 bg-transparent">
              <div class="d-flex justify-content-between mb-3">
                <h3><i class="ni ni-satisfied text-dark text-md opacity-10"></i> Préstamos de {{ $client->nombre }}</h3>
              </div>

              @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
              @endif
              @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
              @endif
               @if (!$tienePrestamoActivo)
                  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nuevoPrestamoModal">
                    Nuevo Préstamo
                  </button>
                @else
                  <div class="alert alert-warning mb-0">
                    Este cliente ya tiene un préstamo activo. No puede solicitar uno nuevo hasta liquidar el actual.
                  </div>
                @endif
                <div class="table-responsive">
              <table class="table table-striped table-responsive">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($loans as $loan)
                    <tr>
                      <td>{{ $loan->id }}</td>
                      <td>${{ number_format($loan->monto, 2) }}</td>
                      <td>{{ $loan->created_at->format('d/m/Y') }}</td>
                      <td>{{ ucfirst($loan->estado) }}</td>
                      <td>
                        <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-primary">Ver Detalle</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              </div>

              {{ $loans->links() }}
            </div>
          </div>
        </div>
      </div>

      @include('partials.footer')
    </div>
  </main>

  {{-- Modal para crear nuevo préstamo --}}
  <div class="modal fade" id="nuevoPrestamoModal" tabindex="-1" aria-labelledby="nuevoPrestamoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="{{ route('loans.store') }}">
        @csrf
        <input type="hidden" name="client_id" value="{{ $client->id }}">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="nuevoPrestamoModalLabel">Nuevo Préstamo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="monto" class="form-label">Monto del préstamo</label>
              <input type="number" name="monto" id="monto" class="form-control" required min="1" step="0.01">
            </div>
            <div class="mb-3">
              <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
              <input type="date" name="fecha_inicio" id="fecha_inicio"  class="form-control " value="fecha_inicio" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Crear Préstamo</button>
          </div>
        </div>
      </form>
    </div>
  </div>

@endsection
