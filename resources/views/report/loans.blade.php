@extends('layouts.app')

@section('content')
<div class="min-height-300 bg-dark position-absolute w-100" style="height: 150px; clip-path: polygon(0 0, 100% 0, 100% 50%, 0 100%);"></div>

@include('partials.sidebar')

<main class="main-content position-relative px-3 pt-4">
  @include('partials.navbar')

  <div class="container-fluid py-4">
    <div class="card shadow-lg border-0">
       

      <div class="card-body bg-white rounded-bottom p-4">
        <h3 class="mb-4">Reporte Completo de Préstamos</h3>
 <div class="d-flex justify-content-end mb-3">
  <a href="{{ route('loans.export') }}" class="btn btn-success">
    <i class="fas fa-file-excel"></i> Exportar a Excel
  </a>
</div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead>
              <tr>
                <th>Préstamo ID</th>
                <th>Cliente</th>
                <th>Usuario (Creador)</th>
                <th>Monto Préstamo</th>
                <th>Interés</th>
                <th>Restante</th>
                <th>Estado Préstamo</th>
                <th>Fecha Inicio</th>
                <th>Fecha Creación</th>
                <th>Semanas (Pagadas / Total)</th>
                <th>Detalle Semanas</th>
              </tr>
            </thead>
            <tbody>
              @foreach($loans as $loan)
                @php
                  $semanasPagadas = $loan->weeks->where('estado', 'pagado')->count();
                  $totalSemanas = $loan->weeks->count();
                @endphp
                <tr>
                  <td>{{ $loan->id }}</td>
                  <td>
                    {{ $loan->client->nombre }}<br>
                    <small>{{ $loan->client->telefono }}</small><br>
                    <small>{{ $loan->client->email }}</small>
                  </td>
                  <td>{{ $loan->client->user ? $loan->client->user->name : 'Sin usuario' }}</td>
                  <td>${{ number_format($loan->monto, 2) }}</td>
                  <td>${{ number_format($loan->interes, 2) }}</td>
                  <td>${{ number_format($loan->restante, 2) }}</td>
                  <td>{{ ucfirst($loan->estado) }}</td>
                  <td>{{ \Carbon\Carbon::parse($loan->fecha_inicio)->format('d/m/Y') }}</td>
                  <td>{{ \Carbon\Carbon::parse($loan->created_at)->format('d/m/Y H:i') }}</td>
                  <td>{{ $semanasPagadas }} / {{ $totalSemanas }}</td>
                  <td>
                    <table class="table table-sm table-bordered mb-0">
                      <thead>
                        <tr>
                          <th># Semana</th>
                          <th>Fecha Pago</th>
                          <th>Monto Pago</th>
                          <th>Restante</th>
                          <th>Estado</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($loan->weeks as $week)
                          <tr>
                            <td>{{ $week->numero_semana }}</td>
                            <td>{{ \Carbon\Carbon::parse($week->fecha_pago)->format('d/m/Y') }}</td>
                            <td>${{ number_format($week->monto_pago, 2) }}</td>
                            <td>${{ number_format($week->restante, 2) }}</td>
                            <td>
                              <span class="badge bg-{{ 
                                $week->estado == 'pagado' ? 'success' : 
                                ($week->estado == 'retraso' ? 'warning' : 'danger') }}">
                                {{ ucfirst($week->estado) }}
                              </span>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  @include('partials.footer')
</main>
@endsection
