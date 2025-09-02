@extends('layouts.app')

@section('titulo')
{{ isset($analisisNt) ? 'Editar' : 'Crear' }} Análisis NT
@endsection

@section("styles")
<style>
    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .form-group label {
        font-weight: 600;
        color: #495057;
    }
    .required:after {
        content: " *";
        color: red;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Mensajes de error -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Card del formulario -->
            <div class="card shadow-lg border-0">
                <div class="card-header card-header-custom">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-{{ isset($analisisNt) ? 'edit' : 'plus' }} mr-2"></i>
                        {{ isset($analisisNt) ? 'Editar' : 'Crear Nuevo' }} Análisis NT
                    </h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ isset($analisisNt) ? route('analisis-nt.update', $analisisNt->id) : route('analisis-nt.store') }}" id="formAnalisisNt">
                        @csrf
                        @if(isset($analisisNt))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <!-- Código Cliente -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_cliente">Código Cliente</label>
                                    <input type="text" 
                                           class="form-control @error('codigo_cliente') is-invalid @enderror" 
                                           id="codigo_cliente" 
                                           name="codigo_cliente" 
                                           value="{{ old('codigo_cliente', $analisisNt->codigo_cliente ?? '') }}"
                                           maxlength="255">
                                    @error('codigo_cliente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Código Medcol -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo_medcol" class="required">Código Medcol</label>
                                    <input type="text" 
                                           class="form-control @error('codigo_medcol') is-invalid @enderror" 
                                           id="codigo_medcol" 
                                           name="codigo_medcol" 
                                           value="{{ old('codigo_medcol', $analisisNt->codigo_medcol ?? '') }}"
                                           maxlength="255" required>
                                    @error('codigo_medcol')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Agrupador -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="agrupador">Agrupador</label>
                                    <input type="text" 
                                           class="form-control @error('agrupador') is-invalid @enderror" 
                                           id="agrupador" 
                                           name="agrupador" 
                                           value="{{ old('agrupador', $analisisNt->agrupador ?? '') }}"
                                           maxlength="255">
                                    @error('agrupador')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- CUMS -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cums" class="required">CUMS</label>
                                    <input type="text" 
                                           class="form-control @error('cums') is-invalid @enderror" 
                                           id="cums" 
                                           name="cums" 
                                           value="{{ old('cums', $analisisNt->cums ?? '') }}"
                                           maxlength="255" required>
                                    @error('cums')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Nombre -->
                        <div class="form-group">
                            <label for="nombre" class="required">Nombre del Medicamento</label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $analisisNt->nombre ?? '') }}"
                                   maxlength="255" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Expediente -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expediente">Expediente</label>
                                    <input type="text" 
                                           class="form-control @error('expediente') is-invalid @enderror" 
                                           id="expediente" 
                                           name="expediente" 
                                           value="{{ old('expediente', $analisisNt->expediente ?? '') }}"
                                           maxlength="255">
                                    @error('expediente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Valor Unitario -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valor_unitario">Valor Unitario</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01" 
                                               min="0"
                                               class="form-control @error('valor_unitario') is-invalid @enderror" 
                                               id="valor_unitario" 
                                               name="valor_unitario" 
                                               value="{{ old('valor_unitario', $analisisNt->valor_unitario ?? '') }}">
                                        @error('valor_unitario')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Frecuencia de Uso -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="frecuencia_uso">Frecuencia de Uso</label>
                                    <input type="text" 
                                           class="form-control @error('frecuencia_uso') is-invalid @enderror" 
                                           id="frecuencia_uso" 
                                           name="frecuencia_uso" 
                                           value="{{ old('frecuencia_uso', $analisisNt->frecuencia_uso ?? '') }}"
                                           maxlength="255">
                                    @error('frecuencia_uso')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contrato -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contrato" class="required">Contrato</label>
                                    <input type="text" 
                                           class="form-control @error('contrato') is-invalid @enderror" 
                                           id="contrato" 
                                           name="contrato" 
                                           value="{{ old('contrato', $analisisNt->contrato ?? '') }}"
                                           maxlength="255" required>
                                    @error('contrato')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Nota:</strong> Los campos marcados con asterisco (*) son obligatorios. 
                            La combinación de Código Medcol, CUMS y Contrato debe ser única en el sistema.
                        </div>

                        <!-- Botones -->
                        <div class="form-group text-center mt-4">
                            <a href="{{ route('analisis-nt.index') }}" class="btn btn-secondary btn-lg mr-3">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save mr-2"></i>
                                {{ isset($analisisNt) ? 'Actualizar' : 'Guardar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("scriptsPlugins")
<script>
$(document).ready(function(){
    // Validación del formulario
    $('#formAnalisisNt').on('submit', function(e) {
        var isValid = true;
        var errors = [];

        // Validar campos requeridos
        if (!$('#codigo_medcol').val().trim()) {
            errors.push('El código medcol es requerido');
            $('#codigo_medcol').addClass('is-invalid');
            isValid = false;
        } else {
            $('#codigo_medcol').removeClass('is-invalid');
        }

        if (!$('#nombre').val().trim()) {
            errors.push('El nombre es requerido');
            $('#nombre').addClass('is-invalid');
            isValid = false;
        } else {
            $('#nombre').removeClass('is-invalid');
        }

        if (!$('#cums').val().trim()) {
            errors.push('El CUMS es requerido');
            $('#cums').addClass('is-invalid');
            isValid = false;
        } else {
            $('#cums').removeClass('is-invalid');
        }

        if (!$('#contrato').val().trim()) {
            errors.push('El contrato es requerido');
            $('#contrato').addClass('is-invalid');
            isValid = false;
        } else {
            $('#contrato').removeClass('is-invalid');
        }

        // Validar valor unitario si está presente
        var valorUnitario = $('#valor_unitario').val();
        if (valorUnitario && (isNaN(valorUnitario) || parseFloat(valorUnitario) < 0)) {
            errors.push('El valor unitario debe ser un número positivo');
            $('#valor_unitario').addClass('is-invalid');
            isValid = false;
        } else {
            $('#valor_unitario').removeClass('is-invalid');
        }

        if (!isValid) {
            e.preventDefault();
            
            // Mostrar errores
            var errorHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                           '<i class="fas fa-exclamation-triangle mr-2"></i>' +
                           '<strong>Por favor corrige los siguientes errores:</strong>' +
                           '<ul class="mb-0 mt-2">';
            
            errors.forEach(function(error) {
                errorHtml += '<li>' + error + '</li>';
            });
            
            errorHtml += '</ul>' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                        '</button></div>';
            
            // Remover alertas anteriores y agregar la nueva
            $('.alert').remove();
            $('.container-fluid .row').first().prepend('<div class="col-12">' + errorHtml + '</div>');
            
            // Hacer scroll hacia arriba para mostrar el error
            $('html, body').animate({scrollTop: 0}, 500);
        }
    });

    // Remover clase de error al escribir en los campos
    $('.form-control').on('input', function() {
        $(this).removeClass('is-invalid');
    });

    // Formatear valor unitario en tiempo real
    $('#valor_unitario').on('input', function() {
        var valor = $(this).val();
        if (valor && !isNaN(valor) && parseFloat(valor) >= 0) {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>
@endsection