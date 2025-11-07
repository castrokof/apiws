@extends("theme.$theme.layout")

@section('titulo')
    Nuevo Código de Proveedor
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
        <span id="form_result"></span>

        <div id="card-drawel" class="card card-info">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">@yield('titulo')</h3>
                <div class="ml-auto d-flex">
                    <a href="{{ route('moleculas.codigos.index', $molecula ?? $codigo->molecula) }}" class="btn btn-danger mx-1">
                        <i class="fa fa-arrow-left"></i> Atrás
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-3">
    <form method="POST" action="{{ isset($codigo) ? route('codigos.update',$codigo) : route('moleculas.codigos.store',$molecula) }}">
        @csrf
        @if(isset($codigo))
            @method('PUT')
        @endif

        @include('moleculas.codigos.partials.form', ['codigo' => $codigo ?? new \App\Models\compras\MoleculaProveedorCodigo])

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save"></i> Guardar
            </button>
        </div>
    </form>
</div>
@endsection
