@extends("theme.$theme.layout")

@section('titulo') Cargar Proveedores por Excel @endsection

@section('styles')
<link href="{{ asset("assets/$theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('contenido')
<div class="row">
  <div class="col-lg-12">
    <div id="card-drawel" class="card card-info">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">@yield('titulo')</h3>
        <div class="ml-auto d-flex">
          <a href="{{ route('moleculas.index') }}" class="btn btn-danger mx-1">
            <i class="fa fa-arrow-left"></i> Atrás
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container mt-3">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('errors_import') && count(session('errors_import')))
    <div class="alert alert-danger">
      <strong>No se importó nada.</strong> Corrige el archivo y vuelve a intentar.
      <ul class="mb-0">
        @foreach(session('errors_import') as $e)
          <li>{!! $e !!}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if(session('skipped') && count(session('skipped')))
    <div class="alert alert-warning">
      <strong>Registros omitidos (duplicados):</strong>
      <ul class="mb-0">
        @foreach(session('skipped') as $s)
          <li>{{ $s }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    <div class="card-body">
      <p class="mb-2 text-muted">
        Estructura esperada: <code>CodigoRfast</code>, <code>NombreProveedor</code>, <code>CodigoProveedor</code>.
        La Primera fila es el encabezado.
      </p>

      <form action="{{ route('moleculas.import.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required accept=".xlsx,.xls,.csv" class="form-control">
        <button type="submit" class="btn btn-primary">Subir y procesar</button>
    </form>
    </div>
  </div>
</div>
@endsection
