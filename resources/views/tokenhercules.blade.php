@extends('layouts.admin')

@section('title', 'Mipres 2.0 - Token Hercules')

@section('styles')
<link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
<style>
    .token-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .token-card h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .token-card p {
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .token-input {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 1rem;
        font-family: 'Courier New', monospace;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .token-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-generate {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 0.75rem 2rem;
        font-size: 1.1rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-generate:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .info-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        display: inline-block;
        margin-top: 1rem;
    }
</style>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-key text-warning mr-2"></i>
                    Mipres 2.0 - Token Hercules
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Mipres 2.0</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- Header Card -->
        <div class="token-card animate__animated animate__fadeIn">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>
                        <i class="fas fa-shield-alt mr-2"></i>
                        Sistema de Autenticación Hercules
                    </h1>
                    <p class="mb-0">Configure el token de acceso para integración con Mipres 2.0</p>
                    <div class="info-badge">
                        <i class="fas fa-info-circle mr-2"></i>
                        Token válido para servicios web Mipres
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-key" style="font-size: 5rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('mensaje'))
        <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
            <i class="icon fas fa-check-circle mr-2"></i>
            <strong>¡Éxito!</strong> {{ session('mensaje') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
            <i class="icon fas fa-exclamation-triangle mr-2"></i>
            <strong>Error:</strong> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <!-- Form Card -->
        <div class="card card-outline card-primary animate__animated animate__fadeInUp">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-2"></i>
                    Configurar Token de Acceso
                </h3>
            </div>
            <div class="card-body">
                <form action="{{ route('tokenhercules1') }}" method="post" id="tokenForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="tokenhercules">
                                    <i class="fas fa-key text-primary mr-1"></i>
                                    Token Hercules
                                    <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="tokenhercules"
                                    id="tokenhercules"
                                    class="form-control token-input"
                                    value="25CF92EF-78A5-40BA-8BFB-41E11B5C572C"
                                    minlength="6"
                                    required
                                    placeholder="Ingrese el token de acceso">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Formato UUID: XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
                                </small>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="submit" id="consultar" class="btn btn-success btn-generate btn-block">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Guardar Token
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Info Section -->
                    <div class="alert alert-info mt-3" role="alert">
                        <h5 class="alert-heading">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Información Importante
                        </h5>
                        <hr>
                        <ul class="mb-0">
                            <li>El token debe ser proporcionado por el sistema Hercules de Mipres</li>
                            <li>Este token permite la autenticación con los servicios web de Mipres 2.0</li>
                            <li>Asegúrese de mantener el token seguro y actualizado</li>
                            <li>Si el token expira, solicite uno nuevo al administrador del sistema</li>
                        </ul>
                    </div>

                </form>
            </div>
        </div>

        <!-- Additional Info Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="fas fa-shield-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Estado de Integración</span>
                        <span class="info-box-number">Activa</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fas fa-sync-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sincronización</span>
                        <span class="info-box-number">Automática</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-gradient-warning">
                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Última Actualización</span>
                        <span class="info-box-number">Hoy</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@section('scriptsPlugins')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$(document).ready(function() {
    // Auto-dismiss alerts after 6 seconds
    setTimeout(function() {
        $('.alert-dismissible').fadeOut('slow');
    }, 6000);

    // Form validation
    $('#tokenForm').on('submit', function(e) {
        const token = $('#tokenhercules').val().trim();

        if (token.length < 6) {
            e.preventDefault();
            swal({
                title: "Token Inválido",
                text: "El token debe tener al menos 6 caracteres",
                icon: "warning",
                button: "Entendido"
            });
            return false;
        }

        // Show loading
        $('#consultar').html('<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...').prop('disabled', true);
    });

    // Copy token to clipboard functionality (opcional)
    $('#tokenhercules').on('dblclick', function() {
        $(this).select();
        document.execCommand('copy');

        // Show toast notification
        toastr.success('Token copiado al portapapeles');
    });
});
</script>
@endsection
