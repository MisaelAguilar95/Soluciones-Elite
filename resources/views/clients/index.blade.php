@extends('layouts.app')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

@section('content')
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  @include('partials.sidebar')

  <main class="main-content position-relative  px-3">


    @include('partials.navbar')

    <div class="container-fluid py-4">
      <div class="row ">
        <div class="col-lg-12 mb-lg-0 mb-4">
          <div class="card">
            <div class="card-body pb-0 pt-3 bg-transparent">
             <div class="d-flex justify-content-between mb-3">
                <h3><i class="ni ni-satisfied text-dark text-md opacity-10"></i> Clientes</h3>
                @if(auth()->user()->level === 'admin')
                <a href="{{ route('clients.create') }}" class="btn btn-primary">+ Nuevo Cliente</a>
                @endif
              </div>
              @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
              @endif
              <div class="table-responsive">
              <table id="clientsTable" class="table table-striped ">
                <thead>   
                  <tr>
                    <th hidden>Creado</th>
                    <th>Nombre</th>
                    <th>CURP</th>
                    <th>Creador</th>
                    <th>Monto</th>
                    <th>Restante</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($clients as $client)
                    @php
                      $prestamoActivo = $client->loans->last(); // ya filtraste activos
                      $estado = $prestamoActivo ? $prestamoActivo->estado : null;
                      $badgeColor = match($estado) {
                        'activo' => 'primary',
                        'pagado' => 'success',
                        'vencido' => 'danger',
                        'retraso' => 'warning',
                        default => 'secondary'
                      };
                    @endphp
                    <tr>
                      <td hidden>{{$client->created_at}}</td>
                      <td><strong>{{ $client->nombre }}</strong></td>
                      <td>{{ $client->curp }}</td>
                      <td>
                        <span class="badge bg-dark">
                          {{ $client->user ? $client->user->name : 'Desconocido' }}
                        </span>
                      </td>
                      <td>
                        @if($prestamoActivo)
                          <span class="text-success fw-bold">
                            ${{ number_format($prestamoActivo->monto, 2) }}
                          </span>
                        @else
                          <span class="text-muted">$0.00</span>
                        @endif
                      </td>
                      <td>
                        @if($prestamoActivo)
                          <span class="text-danger fw-semibold">
                            ${{ number_format($prestamoActivo->montoRestante(), 2) }}
                          </span>
                        @else
                          <span class="text-muted">$0.00</span>
                        @endif
                      </td>
                      <td>
                        <span class="badge bg-{{ $badgeColor }}">
                          {{ $estado ? ucfirst($estado) : 'Sin préstamo activo' }}
                        </span>
                      </td>
                      <td>
                        <a href="{{ route('clients.loans', $client) }}" class="btn btn-sm btn-success" title="Ver Préstamos">
                          <i class="fas fa-money-bill-wave"></i>
                        </a>
                        @if(auth()->user()->level === 'admin')
                          <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-primary" title="Editar Cliente">
                            <i class="fas fa-edit"></i>
                          </a>
                          <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar?')" title="Eliminar Cliente">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form>
                        @else
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              </div>
            
              {{ $clients->links() }}
            </div>
          </div>
        </div>
      </div>
      @include('partials.footer')
    </div>
  </main>
@endsection
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
  $(document).ready(function() {
    $('#clientsTable').DataTable({
  lengthChange: false,
  order: [[0, 'desc']],  // Ordena la primera columna descendente
  language: {
    search: "Buscar:",
    paginate: {
      previous: "Anterior",
      next: "Siguiente"
    },
    zeroRecords: "No se encontraron resultados",
    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
    infoEmpty: "Mostrando 0 a 0 de 0 registros",
    infoFiltered: "(filtrado de _MAX_ registros totales)"
  }
});
  });
</script>


