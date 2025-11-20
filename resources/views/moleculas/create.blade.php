@extends('layouts.admin')

@section('title', 'Crear Molécula')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Crear Molécula</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
          <li class="breadcrumb-item"><a href="{{ route('compras.medcol3') }}">Órdenes de Compra</a></li>
          <li class="breadcrumb-item"><a href="{{ route('moleculas.index') }}">Moléculas</a></li>
          <li class="breadcrumb-item active">Crear</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <!-- Card -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-plus"></i> Nueva Molécula</h3>
          </div>

          <!-- Form -->
          <form method="POST" action="{{ route('moleculas.store') }}">
            @csrf

            <div class="card-body">
              @include('moleculas.partials.form', ['molecula' => new \App\Models\Compras\Molecula])
            </div>

            <div class="card-footer">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
              </button>
              <a href="{{ route('moleculas.index') }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancelar
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>{{-- /container-fluid --}}
</section>{{-- /.content --}}
@endsection
