@extends('layouts.app')

@section('content')
<div class="min-height-300 bg-dark position-absolute w-100"></div>
@include('partials.sidebar')

<main class="main-content position-relative px-3">
    @include('partials.navbar')

    <div class="container-fluid py-4">

        {{-- Card principal --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3>Módulo de Reportes</h3>
            </div>
            <div class="card-body">

                {{-- Formulario de filtros --}}
                <form method="POST" action="{{ route('reportes.buscar') }}" class="row g-3 mb-4">
                    @csrf
                    <div class="col-md-3">
                        <label for="usuario_id" class="form-label">Usuario</label>
                        <select name="usuario_id" id="usuario_id" class="form-select" required>
                            <option value="todos" {{ (isset($usuarioId) && $usuarioId=='todos') ? 'selected' : '' }}>Todos</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}" 
                                    {{ isset($usuarioId) && $usuarioId == $usuario->id ? 'selected' : '' }}>
                                    {{ $usuario->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="fecha_inicio" class="form-label">Fecha inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" 
                               value="{{ $fechaInicio ?? '' }}" required>
                    </div>

                    <div class="col-md-2">
                        <label for="fecha_fin" class="form-label">Fecha fin</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" 
                               value="{{ $fechaFin ?? '' }}" required>
                    </div>

                    <div class="col-md-2">
                        <label for="estado" class="form-label">Estado</label>
                        <select name="estado" id="estado" class="form-select">
                            <option value="">-- Todos --</option>
                            <option value="pagado" {{ (isset($estado) && $estado=='pagado') ? 'selected' : '' }}>Pagado</option>
                            <option value="retraso" {{ (isset($estado) && $estado=='retraso') ? 'selected' : '' }}>Retraso</option>
                            <option value="vencido" {{ (isset($estado) && $estado=='vencido') ? 'selected' : '' }}>Vencido</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </div>
                </form>

                {{-- Mini-dashboard con totales --}}
                @isset($prestamos)
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h6 class="card-title">Total Pagado</h6>
                                <p class="card-text fs-5">${{ number_format($totalPagado, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <h6 class="card-title">Total Pendiente</h6>
                                <p class="card-text fs-5">${{ number_format($totalPendiente, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h6 class="card-title">Total de Préstamos</h6>
                                <p class="card-text fs-5">{{ $totalPrestamos }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <h6 class="card-title">Semanas Pagadas</h6>
                                <p class="card-text fs-5">{{ $semanasPagadas }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endisset

                {{-- Tabla de resultados --}}
                @isset($prestamos)
                <div class="table-responsive">
                    <table class="table table-striped align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Préstamo</th>
                                <th>Cliente</th>
                                <th>Monto</th>
                                <th>Fecha de inicio</th>
                                <th>Semanas/Pagos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prestamos as $loan)
                                <tr>
                                    <td>#{{ $loan->id }}</td>
                                    <td>{{ $loan->client->nombre }}</td>
                                    <td>${{ number_format($loan->monto, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($loan->fecha_inicio)->format('d/m/Y') }}</td>
                                    <td>
                                        <ul class="mb-0">
                                            @foreach($loan->weeks as $week)
                                                <li>
                                                    Semana {{ $week->numero_semana }} — 
                                                    {{ \Carbon\Carbon::parse($week->fecha_pago)->format('d/m/Y') }} — 
                                                    ${{ number_format($week->monto_pago, 2) }} 
                                                    <span class="badge bg-{{ 
                                                        $week->estado === 'pagado' ? 'success' : 
                                                        ($week->estado === 'vencido' ? 'danger' : 
                                                        ($week->estado === 'retraso' ? 'warning' : 'secondary')) }}">
                                                        {{ ucfirst($week->estado) }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay resultados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endisset

            </div>
        </div>

    </div>

    @include('partials.footer')
</main>
@endsection
