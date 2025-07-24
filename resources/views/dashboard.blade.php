@extends('layouts.app')

@section('content')
@php
  use Illuminate\Support\Carbon;
  use Illuminate\Support\Facades\Auth;

  $user = Auth::user();
  $fechaHoy = Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'); 

  // Métricas extras
  $clientesMes = \App\Models\Client::whereMonth('created_at', Carbon::now()->month)
                   ->whereYear('created_at', Carbon::now()->year)
                   ->count();

  $prestamosMes = \App\Models\Loan::whereMonth('created_at', Carbon::now()->month)
                   ->whereYear('created_at', Carbon::now()->year)
                   ->count();
@endphp

<div class="min-height-300 position-absolute w-100" style="background-color: #344666;"></div>

@include('partials.sidebar')

<main class="main-content position-relative px-4 py-4" style="min-height: 100vh;">
  @include('partials.navbar')

  <div class="container-fluid">
    <div class="mb-3 text-white d-flex justify-content-between align-items-center" style="font-size: 1.2rem;">
      <div>
        <strong>Bienvenido:</strong> {{ $user->name }}
      </div>
      <div>
        <strong>Fecha:</strong> {{ ucfirst($fechaHoy) }}
      </div>
    </div>

    <h2 class="text-white mb-4">Dashboard</h2>

    {{-- Resumen rápido --}}
    <div class="row g-4 mb-5">
      <div class="col-md-2">
        <div class="card shadow-lg rounded-4 animate__animated animate__fadeInUp" style="overflow: hidden;">
          <div class="card-header text-white fw-bold" style="background: #007bff;">
            Total Clientes
          </div>
          <div class="card-body text-white d-flex align-items-center" style="background-color: #344666;">
            <div class="me-3 fs-1">
              <i class="fas fa-users"></i>
            </div>
            <div>
              <h3 class="fw-bold m-0  text-white">{{ $totalClients }}</h3>
            </div>
          </div>
        </div>
      </div>
     {{-- Préstamos Activos --}}
      <div class="col-md-2">
        <div class="card shadow-lg rounded-4 animate__animated animate__fadeInUp animate__delay-1s" style="overflow: hidden;">
          <div class="card-header text-white fw-bold" style="background: #198754;">
            Préstamos Activos
          </div>
          <div class="card-body text-white d-flex align-items-center" style="background-color: #344666;">
            <div class="me-3 fs-1">
              <i class="fas fa-hand-holding-dollar"></i>
            </div>
            <div>
              <h3 class="fw-bold m-0 text-white">{{ $totalLoansActive }}</h3>
            </div>
          </div>
        </div>
      </div>

    {{-- Préstamos Pagados --}}
    <div class="col-md-2">
      <div class="card shadow-lg rounded-4 animate__animated animate__fadeInUp animate__delay-2s" style="overflow: hidden;">
        <div class="card-header text-white fw-bold" style="background: #dc3545;">
          Préstamos Pagados
        </div>
        <div class="card-body text-white d-flex align-items-center" style="background-color: #344666;">
          <div class="me-3 fs-1">
            <i class="fas fa-check-circle"></i>
          </div>
          <div>
            <h3 class="fw-bold m-0 text-white">{{ $totalLoansPaid }}</h3>
          </div>
        </div>
      </div>
    </div>

    {{-- Monto Prestado --}}
    <div class="col-md-2">
      <div class="card shadow-lg rounded-4 animate__animated animate__fadeInUp animate__delay-3s" style="overflow: hidden;">
        <div class="card-header text-white fw-bold" style="background: #fd7e14;">
          Monto Total Prestado
        </div>
        <div class="card-body text-white d-flex align-items-center" style="background-color: #344666;">
          <div>
            <h4 class="fw-bold m-0 text-white">${{ number_format($totalAmountLoaned, 2) }}</h4>
          </div>
        </div>
      </div>
    </div>

    {{-- Clientes Nuevos --}}
    <div class="col-md-2">
      <div class="card shadow-lg rounded-4 animate__animated animate__fadeInUp animate__delay-4s" style="overflow: hidden;">
        <div class="card-header text-white fw-bold" style="background: #6f42c1;">
          Clientes Nuevos (Mes)
        </div>
        <div class="card-body text-white d-flex align-items-center" style="background-color: #344666;">
          <div class="me-3 fs-1">
            <i class="fas fa-user-plus"></i>
          </div>
          <div>
            <h3 class="fw-bold m-0 text-white"  >{{ $clientesMes }}</h3>
          </div>
        </div>
      </div>
    </div>

    {{-- Préstamos Nuevos --}}
    <div class="col-md-2">
      <div class="card shadow-lg rounded-4 animate__animated animate__fadeInUp animate__delay-5s" style="overflow: hidden;">
        <div class="card-header text-white fw-bold" style="background: #20c997;">
          Préstamos Nuevos (Mes)
        </div>
        <div class="card-body text-white d-flex align-items-center" style="background-color: #344666;">
          <div class="me-3 fs-1">
            <i class="fas fa-file-invoice-dollar"></i>
          </div>
          <div>
            <h3 class="fw-bold m-0 text-white"  >{{ $prestamosMes }}</h3>
          </div>
        </div>
      </div>
    </div>

    {{-- Gráficos principales --}}
    <div class="row g-4 mb-5">
      <div class="col-lg-6">
        <div class="card shadow-sm rounded-4">
          <div class="card-header bg-primary text-white fw-semibold">
            Préstamos por Estado
          </div>
          <div class="card-body">
            <canvas id="loansStatusChart"></canvas>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card shadow-sm rounded-4">
          <div class="card-header bg-success text-white fw-semibold">
            Pagos por Estado
          </div>
          <div class="card-body">
            <canvas id="paymentsStatusChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    {{-- Estadísticas adicionales --}}
    <div class="row g-4">
      <div class="col-lg-6">
        <div class="card shadow-sm rounded-4">
          <div class="card-header bg-info text-white fw-semibold">
            % Préstamos Activos vs Totales
          </div>
          <div class="card-body">
            @php
              $totalLoans = $totalLoansActive + $totalLoansPaid;
              $percentActive = $totalLoans ? round(($totalLoansActive / $totalLoans) * 100, 2) : 0;
              $percentPaid = $totalLoans ? round(($totalLoansPaid / $totalLoans) * 100, 2) : 0;
            @endphp

            <div class="mb-3">
              <div class="d-flex justify-content-between">
                <span>Activos</span>
                <span>{{ $percentActive }}%</span>
              </div>
              <div class="progress" style="height: 20px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentActive }}%" aria-valuenow="{{ $percentActive }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

            <div>
              <div class="d-flex justify-content-between">
                <span>Pagados</span>
                <span>{{ $percentPaid }}%</span>
              </div>
              <div class="progress" style="height: 20px;">
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $percentPaid }}%" aria-valuenow="{{ $percentPaid }}" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card shadow-sm rounded-4">
          <div class="card-header bg-warning text-dark fw-semibold">
            Resumen de Estados de Préstamos
          </div>
          <div class="card-body">
            <ul class="list-group">
              @foreach ($loansCountByStatus as $estado => $count)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span class="text-capitalize">{{ $estado }}</span>
                  <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>

    </div>

  </div>

  @include('partials.footer')
</main>

{{-- Animate.css --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const loansStatusLabels = {!! json_encode(array_keys($loansCountByStatus)) !!};
  const loansStatusData = {!! json_encode(array_values($loansCountByStatus)) !!};

  const ctxLoans = document.getElementById('loansStatusChart').getContext('2d');
  new Chart(ctxLoans, {
    type: 'bar',
    data: {
      labels: loansStatusLabels,
      datasets: [{
        label: 'Cantidad de Préstamos',
        data: loansStatusData,
        backgroundColor: '#0d6efd'
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  });

  const paymentsStatusLabels = {!! json_encode(array_keys($paymentsCountByStatus)) !!};
  const paymentsStatusData = {!! json_encode(array_values($paymentsCountByStatus)) !!};

  const ctxPayments = document.getElementById('paymentsStatusChart').getContext('2d');
  new Chart(ctxPayments, {
    type: 'doughnut',
    data: {
      labels: paymentsStatusLabels,
      datasets: [{
        label: 'Cantidad de Pagos',
        data: paymentsStatusData,
        backgroundColor: ['#198754', '#dc3545', '#ffc107', '#6c757d']
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'right' }
      }
    }
  });
</script>
@endsection
