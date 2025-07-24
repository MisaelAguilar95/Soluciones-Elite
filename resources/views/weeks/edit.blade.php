@extends('layouts.app')

@section('content')
<div class="min-height-300 bg-dark position-absolute w-100"></div>
@include('partials.sidebar')

<main class="main-content position-relative border-radius-lg">
  @include('partials.navbar')

  <div class="container py-5">
    <div class="card">
      <div class="card-header bg-gradient-primary text-white">
        <h4>Editar Semana #{{ $week->numero_semana }}</h4>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('weeks.update', $week) }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="fecha_pago" class="form-label">Fecha de Pago</label>
            <input type="date" name="fecha_pago" class="form-control" value="{{ old('fecha_pago', \Carbon\Carbon::parse($week->fecha_pago)->format('Y-m-d')) }}" required>
          </div>

          <div class="mb-3">
            <label for="monto_pago" class="form-label">Monto a Pagar</label>
            <input type="number" step="0.01" name="monto_pago" class="form-control" value="{{ old('monto_pago', $week->monto_pago) }}" required>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="{{ route('loans.weeks', $week->loan_id) }}" class="btn btn-secondary">Cancelar</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  @include('partials.footer')
</main>
@endsection
