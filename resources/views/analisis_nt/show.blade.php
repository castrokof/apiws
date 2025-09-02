@extends('layouts.app')

@section('titulo')
Detalles del Análisis NT
@endsection

@section("styles")
<style>
    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .detail-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
    }
    .detail-value {
        color: #212529;
        padding: 8px 12px;
        background-color: #f8f9fa;
        border-radius: 4px;
        border-left: 3px solid #667eea;
        margin-bottom: 15px;
    }
    .detail-value.empty {
        color: #6c757d;
        font-style: italic;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Card de detalles -->
            <div class="card shadow-lg border-0">
                <div class="card-header card-header-custom">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-eye mr-2"></i>
                                Detalles del Análisis NT
                            </h3>
                            <small class="text-light">ID: {{ $analisisNt->id }}</small>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="btn-group" role="group">
                                <a href="{{ route('analisis-nt.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-arrow-left mr-1"></i> Volver
                                </a>
                                <a href="{{ route('analisis-nt.edit', $analisisNt->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Columna izquierda -->
                        <div class="col-md-6">
                            <!-- Código Cliente -->
                            <div class="form-group">
                                <div class="detail-label">
                                    <i class="fas fa-user-tag mr-1"></i> Código Cliente
                                </div>
                                <div class="detail-value {{ empty($analisisNt->codigo_cliente) ? 'empty' : '' }}">
                                    {{ $analisisNt->codigo_cliente ?: 'No especificado' }}
                                </div>
                            </div>

                            <!-- Código Medcol -->
                            <div class="form-group">
                                <div class="detail-label">
                                    <i class="fas fa-code mr-1"></i> Código Medcol
                                </div>
                                <div class="detail-value">
                                    <strong>{{ $analisisNt->codigo_medcol }}</strong>
                                </div>
                            </div>

                            <!-- Agrupador -->
                            <div class="form-group">
                                <div class="detail-label">
                                    <i class="fas fa-layer-group mr-1"></i> Agrupador
                                </div>
                                <div class="detail-value {{ empty($analisisNt->agrupador) ? 'empty' : '' }}">
                                    {{ $analisisNt->agrupador ?: 'No especificado' }}
                                </div>
                            </div>

                            <!-- CUMS -->
                            <div class="form-group">
                                <div class="detail-label">
                                    <i class="fas fa-barcode mr-1"></i> CUMS
                                </div>
                                <div class="detail-value">
                                    <strong>{{ $analisisNt->cums }}</strong>
                                </div>
                            </div>

                            <!-- Expediente -->
                            <div class="form-group">
                                <div class="detail-label">
                                    <i class="fas fa-folder mr-1"></i> Expediente
                                </div>
                                <div class="detail-value {{ empty($analisisNt->expediente) ? 'empty' : '' }}">
                                    {{ $analisisNt->expediente ?: 'No especificado' }}
                                </div>
                            </div>
                        </div>

                        <!-- Columna derecha -->
                        <div class="col-md-6">
                            <!-- Nombre -->
                            <div class="form-group">
                                <div class="detail-label">
                                    <i class="fas fa-pills mr-1"></i> Nombre del Medicamento
                                </div>
                                <div class="detail-value">
                                    <strong>{{ $analisisNt->nombre }}</strong>
                                </div>
                            </div>

                            <!-- Valor Unitario -->
                            <div class="form-group">
                                <div class="detail-label">
                                    <i class="fas fa-dollar-sign mr-1"></i> Valor Unitario
                                </div>
                                <div class="detail-value {{ empty($analisisNt->valor_unitario) ? 'empty' : '' }}">
                                    @if($analisisNt->valor_unitario)
                                        <span class="text-success font-weight-bold">
                                            ${{ number_format($analisisNt->valor_unitario, 2, ',', '.') }}
                                        </span>
                                    @else
                                        No especificado
                                    @endif
                                </div>
                            </div>

                            <!-- Frecuencia de Uso -->
                            <div class="form-group">
                                <div class="detail-label">
                                    <i class="fas fa-clock mr-1"></i> Frecuencia de Uso
                                </div>
                                <div class="detail-value {{ empty($analisisNt->frecuencia_uso) ? 'empty' : '' }}">
                                    {{ $analisisNt->frecuencia_uso ?: 'No especificado' }}
                                </div>
                            </div>

                            <!-- Contrato -->
                            <div class="form-group">
                                <div class="detail-label">
                                    <i class="fas fa-file-contract mr-1"></i> Contrato
                                </div>
                                <div class="detail-value">
                                    <strong>{{ $analisisNt->contrato }}</strong>
                                </div>
                            </div>

                            <!-- Fechas del sistema -->
                            <div class="form-group">
                                <div class="detail-label">
                                    <i class="fas fa-calendar mr-1"></i> Información del Sistema
                                </div>
                                <div class="detail-value">
                                    <small>
                                        <strong>Creado:</strong> {{ $analisisNt->created_at->format('d/m/Y H:i:s') }}<br>
                                        <strong>Actualizado:</strong> {{ $analisisNt->updated_at->format('d/m/Y H:i:s') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de validación única -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle mr-2"></i>Información de Identificación Única</h6>
                                <p class="mb-0">
                                    Este registro se identifica únicamente por la combinación de:
                                    <strong>Código Medcol ({{ $analisisNt->codigo_medcol }})</strong> + 
                                    <strong>CUMS ({{ $analisisNt->cums }})</strong> + 
                                    <strong>Contrato ({{ $analisisNt->contrato }})</strong>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('analisis-nt.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left mr-2"></i>Volver al Listado
                                </a>
                                <a href="{{ route('analisis-nt.edit', $analisisNt->id) }}" class="btn btn-warning btn-lg">
                                    <i class="fas fa-edit mr-2"></i>Editar Registro
                                </a>
                                <button class="btn btn-danger btn-lg" onclick="eliminar({{ $analisisNt->id }})">
                                    <i class="fas fa-trash mr-2"></i>Eliminar Registro
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scriptsPlugins")
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Función para eliminar registro
function eliminar(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        html: 'Se eliminará el registro con:<br>' +
              '<strong>Código Medcol:</strong> {{ $analisisNt->codigo_medcol }}<br>' +
              '<strong>Nombre:</strong> {{ $analisisNt->nombre }}<br>' +
              '<strong>CUMS:</strong> {{ $analisisNt->cums }}<br>' +
              '<strong>Contrato:</strong> {{ $analisisNt->contrato }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<i class="fas fa-trash mr-1"></i> Sí, eliminar',
        cancelButtonText: '<i class="fas fa-times mr-1"></i> Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear formulario para enviar DELETE
            var form = $('<form>', {
                'method': 'POST',
                'action': '/analisis-nt/' + id
            });
            form.append($('<input>', {
                'type': 'hidden',
                'name': '_token',
                'value': '{{ csrf_token() }}'
            }));
            form.append($('<input>', {
                'type': 'hidden',
                'name': '_method',
                'value': 'DELETE'
            }));
            $('body').append(form);
            form.submit();
        }
    });
}
</script>
@endsection