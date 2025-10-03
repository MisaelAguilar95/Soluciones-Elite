@extends('layouts.app')

@section('content')
<div class="min-height-300 bg-dark position-absolute w-100"></div>
@include('partials.sidebar')

<main class="main-content position-relative px-3">
    @include('partials.navbar')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-body pb-0 pt-3 bg-transparent">

                        <!-- Fila con botones y título -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <!-- Botón regresar -->
                            <button class="btn btn-primary" onclick="history.back()">
                                <i class="fas fa-arrow-left"></i> 
                            </button>

                            <!-- Título centrado -->
                            <h3 class="m-0">
                                <i class="ni ni-satisfied text-dark text-md opacity-10"></i>
                                Préstamos de {{ $client->nombre }}
                            </h3>

                            <!-- Botón nuevo préstamo -->
                             @if(auth()->user()->level === 'admin')
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nuevoPrestamoModal">
                                Nuevo Préstamo
                            </button>
                            @endif
                        </div>

                        <!-- Mensajes de sesión -->
                        @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <!-- Tabla de préstamos -->
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
                                        <td>{{ $loan->fecha_inicio }}</td>
                                        <td>{{ ucfirst($loan->estado) }}</td>
                                        <td>
                                            <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-primary">
                                                Ver Detalle
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación Laravel -->
                        <div class="d-flex justify-content-end mt-3">
                            {{ $loans->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @include('partials.footer')
    </div>
</main>

<!-- Modal para crear nuevo préstamo -->
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
                        <label for="folio_pagare" class="form-label">Folio Pagaré</label>
                        <input type="number" name="folio_pagare" id="folio_pagare" class="form-control" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
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
