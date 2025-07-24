
@extends('layouts.app')

@section('content')
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  @include('partials.sidebar')

 <main class="main-content position-relative px-3">
    @include('partials.navbar')

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-12 mb-lg-0 mb-4">
          <div class="card">
            <div class="card-header pb-0 pt-3 bg-transparent">
            <div class="container py-4">
                <h3 class="mb-4">Nuevo Cliente</h3>

                @if ($errors->any())
                  <div class="alert alert-danger">
                    <strong>Ups!</strong> Hubo algunos errores con tu formulario.<br><br>
                    <ul>
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif
              
                <form action="{{ route('clients.store') }}" method="POST">
                  @csrf
                
                  @include('clients.partials.form')
                
                  <div class="text-end">
                    <button type="submit" class="btn btn-success">Guardar Cliente</button>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
      @include('partials.footer')
    </div>
  </main>
@endsection