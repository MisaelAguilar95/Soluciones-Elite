@extends('layouts.app')

@section('content')

<div class="min-height-300 bg-dark position-absolute w-100"></div>
@include('partials.sidebar')

<main class="main-content position-relative px-3">
  @include('partials.navbar')

  <div class="container-fluid py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-lg border-0">
          <div class="card-header bg-white pb-0 rounded-top-4">
            <h5 class="mb-0">✏️ Editar Pago — Semana #{{ $week->numero_semana }}</h5>
            <p class="text-sm text-muted">
              Préstamo #{{ $week->loan->id }} — Cliente: {{ $week->loan->client->nombre }}
            </p>
          </div>
      <div class="card-body">
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('weeks.update', $week) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="fecha_pago" class="form-label">Fecha de pago:</label>
            <input type="date" name="fecha_pago" id="fecha_pago"
                   class="form-control @error('fecha_pago') is-invalid @enderror"
                   value="{{ old('fecha_pago', $week->fecha_pago) }}" required>
            @error('fecha_pago')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="monto_pago" class="form-label">Monto a pagar:</label>
            <input type="number" step="0.01" name="monto_pago" id="monto_pago"
       class="form-control"
       value="{{ number_format($week->monto_pago, 2) }}"
       readonly>

            @error('monto_pago')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="restante" class="form-label">Monto restante:</label>
            <input type="number" step="0.01" name="restante" id="restante"
       class="form-control @error('restante') is-invalid @enderror"
       value="{{ old('restante', $week->restante) }}"
       min="0" max="{{ $week->monto_pago }}" required>

            @error('restante')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex justify-content-between">
            <a href="{{ route('loans.show', $week->loan_id) }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
```

  </div>

@include('partials.footer')

</main>
@endsection
