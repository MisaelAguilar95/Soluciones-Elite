@extends('layouts.app')

@section('content')
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  @include('partials.sidebar')

  <main class="main-content position-relative px-3">
    @include('partials.navbar')

    <div class="container-fluid py-4">
  
        <div class="card mb-4">
          <div class="card-header">
             <div class="d-flex justify-content-between align-items-center mb-3">
              <!-- Botón regresar -->
              <button class="btn btn-primary" onclick="history.back()">
                  <i class="fas fa-arrow-left"></i> 
              </button>
            </div>
              <h3>Detalle del Préstamo #{{ $loan->id }} — Cliente: {{ $loan->client->nombre }}</h3>
            <strong>Préstamo #{{ $loan->id }}</strong> — Monto: ${{ number_format($loan->monto, 2) }} — Inicio: {{ \Carbon\Carbon::parse($loan->created_at)->format('d/m/Y') }}
          </div>
         
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Semana</th>
                    <th>Fecha de Pago</th>
                    <th>Monto a pagar</th>
                    <th>Monto pagado</th>
                    <th>Restante</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($loan->weeks as $week)
                  <tr>
                    <td>{{ $week->numero_semana }}</td>
                    <td>{{ \Carbon\Carbon::parse($week->fecha_pago)->format('d/m/Y') }}</td>
                    <td>${{ number_format($week->monto_pago, 2) }}</td> <!-- Monto a pagar original -->
                    <td>${{ number_format($week->monto_pago - $week->restante, 2) }}</td> <!-- Monto pagado -->
                    <td>${{ number_format($week->restante, 2) }}</td> <!-- Monto restante -->
                    <td>
                      <span class="badge bg-{{ 
                        $week->estado === 'pagado' ? 'success' : 
                        ($week->estado === 'vencido' ? 'danger' : 
                        ($week->estado === 'retraso' ? 'warning' : 'secondary')) }}">
                        {{ ucfirst($week->estado) }}
                      </span>
                    </td>
                    <td>
                      @if($week->estado !== 'pagado')
                        <form action="{{ route('weeks.pagar', $week) }}" method="POST" class="d-inline">
                          @csrf
                          <button class="btn btn-sm btn-success" title="Marcar como pagado">✔</button>
                        </form>
                      @endif
                       <a href="{{ route('weeks.abonoForm', $week) }}" class="btn btn-sm btn-primary" title="Abonar a esta semana">💵 Abonar</a>

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
