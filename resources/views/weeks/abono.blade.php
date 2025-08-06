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
            <h5 class="mb-0">💵 Abonar a la Semana #{{ $week->numero_semana }} — Préstamo #{{ $week->loan->id }}</h5>
            <p class="text-sm text-muted">Cliente: {{ $week->loan->client->nombre }}</p>
          </div>

          <div class="card-body">
            @if (session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('weeks.procesarAbono', $week) }}" method="POST">
              @csrf

              <div class="mb-3">
                <label for="abono" class="form-label">Monto a abonar:</label>
                <input type="number" step="0.01" name="abono" id="abono"
                class="form-control @error('abono') is-invalid @enderror"
                min="0.01" max="{{ $week->loan->restante }}" required>

                @error('abono')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted d-block">
  Saldo pendiente de la semana: ${{ number_format($week->monto_pago, 2) }}
</small>
<small class="text-muted d-block">
  Puedes abonar más del saldo de esta semana. El sistema aplicará automáticamente el abono a las semanas siguientes.
</small>

              </div>

              <div class="mb-3">
                <label class="form-label">Restante del préstamo:</label>
                <p class="form-control-plaintext">${{ number_format($week->loan->restante, 2) }}</p>
              </div>

              <div class="d-flex justify-content-between">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-success">Registrar Abono</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  @include('partials.footer')
</main>
@endsection
