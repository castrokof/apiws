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
                <h3 class="card-title">Administrar Odenes de Compra - Editar</h3>
                
                <div class="ml-auto d-flex">
                    <a href="{{ route('ordenes.detalle', $orden->num_orden_compra) }}" class="btn btn-warning mx-1">
                            <i class="fas fa-pencil-alt"></i> Ver Detalles
                        </a> 
                    <a href="{{ route('compras.medcol3') }}" class="btn btn-danger mx-1">
                        <i class="fa fa-arrow-left"></i> Atrás
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <h2>Editar Orden #{{ $orden->orden_de_compra }}</h2>

    <form action="{{ route('ordenes.update', $orden->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="orden_de_compra">Orden de Compra</label>
             <input type="text" class="form-control" name="orden_de_compra" value="{{ old('orden_de_compra', $orden->orden_de_compra) }}" disabled>
        </div>
        <div class="form-group">
            <label for="fecha">Fecha Pedido</label>
            <input type="date" class="form-control" name="fecha" value="{{ old('fecha', $orden->fecha) }}">
        </div>
        <div class="form-group">
            <label for="estado">Estado</label>
            <select class="form-control" name="estado">
                <option value="Pendiente" {{ $orden->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="Completada" {{ $orden->estado == 'Completada' ? 'selected' : '' }}>Completada</option>
                <option value="Anulada" {{ $orden->estado == 'Anulada' ? 'selected' : '' }}>Anulada</option>
            </select>
        </div>
        <div class="form-group">
            <label for="observaciones">Observaciones</label>
            <textarea class="form-control" name="observaciones" rows="5">{{ old('observaciones', $orden->observaciones) }}</textarea>
        </div>

        

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('compras.medcol3') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
