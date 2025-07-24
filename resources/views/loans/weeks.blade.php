@extends('layouts.app')

@section('content')
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  @include('partials.sidebar')

  <main class="main-content position-relative border-radius-lg">
    @include('partials.navbar')

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h4>Semanas del Préstamo #{{ $loan->id }}</h4>
              <p>Cliente: <strong>{{ $loan->client->nombre }}</strong> — Monto: <strong>$ {{ number_format($loan->monto, 2) }}</strong></p>
            </div>

            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-3">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th># Semana</th>
                      <th>Fecha de Pago</th>
                      <th>Monto</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($weeks as $week)
                      <tr>
                        <td>Semana {{ $week->numero_semana }}</td>
                        <td>{{ \Carbon\Carbon::parse($week->fecha_pago)->format('d/m/Y') }}</td>
                        <td>$ {{ number_format($week->monto_pago, 2) }}</td>
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
                              <button class="btn btn-sm btn-success">✔ Pagar</button>
                            </form>
                          @endif
                        
                          @if(auth()->user()->level === 'admin')
                            <a href="{{ route('weeks.edit', $week) }}" class="btn btn-sm btn-warning">✏ Editar</a>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                </tbody>

                </table>

                @if ($weeks->isEmpty())
                  <div class="text-center text-muted mt-3">Este préstamo aún no tiene semanas registradas.</div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      @include('partials.footer')
    </div>
  </main>
@endsection
