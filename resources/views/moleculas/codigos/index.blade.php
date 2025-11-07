@extends("theme.$theme.layout")

@section('titulo')
    Códigos de Proveedor
@endsection

@section('styles')
<link href="{{ asset("assets/$theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('contenido')
<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')
        <span id="form_result"></span>

        <div id="card-drawel" class="card card-info">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Códigos de [{{ $molecula->codigo_rfast }}]-[{{$molecula->descripcion}}]</h3>
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
    <div class="mb-3">
        <a href="{{ route('moleculas.codigos.create', $molecula) }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Nuevo Código
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <thead class="thead-light">
                <tr>
                    <th>Nombre Proveedor</th>
                    <th>Código Proveedor</th>
                    <th>Activo</th>
                    <th style="width:130px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($codigos as $c)
                    <tr>
                        <td>{{ $c->nombre_proveedor }}</td>
                        <td>{{ $c->codigo_proveedor }}</td>
                        <td>
                            <span class="badge badge-{{ $c->activo ? 'success' : 'secondary' }}">
                                {{ $c->activo ? 'Sí' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('codigos.edit', $c) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('codigos.destroy', $c) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este código?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No hay códigos registrados</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
