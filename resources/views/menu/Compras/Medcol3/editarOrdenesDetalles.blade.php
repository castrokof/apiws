@extends("theme.$theme.layout") {{-- Ajusta si usas otro layout --}}
@section('titulo')
    Ordenes de Compras
@endsection
@section('contenido')

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')
        
        <span id="form_result"></span>
        <div id="card-drawel" class="card card-info">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Administrar Odenes de Compra - Editar Molecula</h3>
                
                <div class="ml-auto d-flex">
                    <a href="{{ route('ordenes.detalle', $detalle->numeroOrden) }}" class="btn btn-danger mx-1">
                        <i class="fa fa-arrow-left"></i> Atrás
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <h2>Editar Molecula #{{ $detalle->nombre }}</h2>

    <form action="{{ route('ordenesDetalle.update', $detalle->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="codigo">Codigo</label>
            <input type="text" class="form-control" name="codigo" value="{{ old('codigo', $detalle->codigo) }}">
        </div>
        <div class="form-group">
            <label for="cums">Cums</label>
            <input type="text" class="form-control" name="cums" value="{{ old('cums', $detalle->cums) }}">
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad Solicitada</label>
             <input type="number" class="form-control" name="cantidad" value="{{ old('cantidad', $detalle->cantidad) }}">
        </div>
        <div class="form-group">
            <label for="cantidadEntregada">Cantidad Entregada</label>
             <input type="number" class="form-control" name="cantidadEntregada" value="{{ old('cantidadEntregada', $detalle->cantidadEntregada) }}">
        </div>
        <div class="form-group">
            <label for="precio">Precio Pactado</label>
            <input type="number" class="form-control" name="precio" value="{{ old('precio', $detalle->precio) }}">
        </div>
        <div class="form-group">
            <label for="precio">Precio Facturado</label>
            <input type="number" class="form-control" name="valorFacturado" value="{{ old('valorFacturado', $detalle->valorFacturado) }}">
        </div>
        
        <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control" name="estado">
                <option value="Pendiente" {{ $detalle->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="Completa" {{ $detalle->estado == 'Completa' ? 'selected' : '' }}>Completa</option>
                <option value="Anulada" {{ $detalle->estado == 'Anulada' ? 'selected' : '' }}>Anulada</option>
            </select>
        </div>
        <div class="form-group">
            <label for="observaciones">Observaciones</label>
            <textarea class="form-control" name="observaciones" rows="5">{{ old('observaciones', $detalle->observaciones) }}</textarea>
        </div>

        

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('ordenes.detalle', $detalle->numeroOrden) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
