@extends('layouts.admin')

@section('title', 'Editar Molécula')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Editar Molécula</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
          <li class="breadcrumb-item"><a href="{{ route('compras.medcol3') }}">Órdenes de Compra</a></li>
          <li class="breadcrumb-item"><a href="{{ route('moleculas.index') }}">Moléculas</a></li>
          <li class="breadcrumb-item active">Editar</li>
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
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-edit"></i> Editar Molécula: {{ $molecula->codigo_rfast }}</h3>
          </div>

          <!-- Form -->
          <form method="POST" action="{{ route('moleculas.update', $molecula) }}">
            @csrf
            @method('PUT')

            <div class="card-body">
              @include('moleculas.partials.form', ['molecula' => $molecula])
            </div>

            <div class="card-footer">
              <button type="submit" class="btn btn-warning">
                <i class="fas fa-save"></i> Actualizar
              </button>
              <a href="{{ route('moleculas.index') }}" class="btn btn-default">
                <i class="fas fa-times"></i> Cancelar
              </a>
              <button type="button" class="btn btn-danger float-right" onclick="confirmarEliminacion()">
                <i class="fas fa-trash"></i> Eliminar
              </button>
            </div>
          </form>

          <!-- Form de eliminación (oculto) -->
          <form id="formEliminar" method="POST" action="{{ route('moleculas.destroy', $molecula) }}" style="display: none;">
            @csrf
            @method('DELETE')
          </form>
        </div>
      </div>
    </div>
  </div>{{-- /container-fluid --}}
</section>{{-- /.content --}}
@endsection

@section('scripts')
<script>
function confirmarEliminacion() {
  Swal.fire({
    title: '¿Estás seguro?',
    text: "Esta acción eliminará la molécula permanentemente",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('formEliminar').submit();
    }
  });
}
</script>
@endsection
