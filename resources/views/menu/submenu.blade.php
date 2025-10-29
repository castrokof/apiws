@extends('layouts.admin')

@section('title', 'Pendientes - MedCol')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-capsules text-primary mr-2"></i>
                    Gestión de Pendientes
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Pendientes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- MedCol API Pendientes - Card Principal -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body p-0">
                        <!-- Header Principal -->
                        <div class="text-center py-4 text-white">
                            <div class="mb-3">
                                <i class="fas fa-server fa-3x mb-2" style="color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></i>
                            </div>
                            <h4 class="font-weight-bold mb-1" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">MEDCOL API PENDIENTES</h4>
                            <p class="mb-0 small opacity-75">Sistema de Gestión Farmacéutica Avanzada</p>
                        </div>

                        <!-- Sección Principal -->
                        <div class="px-4 pb-3">
                            <div class="card border-0 shadow-sm mb-3" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                                <div class="card-body p-3">
                                    <div class="text-center mb-2">
                                        <i class="fas fa-cogs fa-2x text-primary mb-2"></i>
                                        <h6 class="font-weight-bold text-dark mb-1">🚀 ACCESO PRINCIPAL</h6>
                                    </div>

                                    @if(Auth::user()->hasPermission('dashboard.view'))
                                    <a href="{{ route('dashboard') }}" class="btn btn-success btn-lg btn-block shadow-sm mb-2" style="border-radius: 15px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="fas fa-chart-bar mr-2"></i>
                                        Dashboard General
                                        <span class="badge badge-light ml-2">NUEVO</span>
                                    </a>
                                    @endif

                                    @if(Auth::user()->hasPermission('medcol6.pendientes.view'))
                                    <a href="{{ route('medcol6.pendientes') }}" class="btn btn-primary btn-lg btn-block shadow-sm" style="border-radius: 15px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="fas fa-capsules mr-2"></i>
                                        Gestionar Pendientes
                                    </a>
                                    @endif

                                    <small class="text-muted d-block text-center mt-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Sistema principal de gestión de medicamentos pendientes
                                    </small>
                                </div>
                            </div>

                            <!-- Sección de Consultas -->
                            <div class="card border-0" style="background: rgba(255,255,255,0.9); backdrop-filter: blur(10px);">
                                <div class="card-header bg-transparent border-0 py-2">
                                    <h6 class="mb-0 text-center text-muted font-weight-bold">
                                        <i class="fas fa-search mr-1"></i>
                                        HERRAMIENTAS DE CONSULTA
                                    </h6>
                                </div>
                                <div class="card-body p-2">
                                    <div class="list-group list-group-flush">
                                        @if(Auth::user()->hasPermission('medcol6.saldos.view'))
                                        <a href="{{ route('medcol6.saldos') }}" class="list-group-item list-group-item-action border-0 rounded mb-1 py-2" style="background: rgba(248,249,250,0.8);">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 mr-3">
                                                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                        <i class="fas fa-warehouse text-white fa-sm"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 font-weight-bold text-dark">Consultar Saldos</h6>
                                                    <small class="text-muted">Inventario y disponibilidad</small>
                                                </div>
                                                <span class="badge badge-success">CONSULTA</span>
                                            </div>
                                        </a>
                                        @endif

                                        @if(Auth::user()->hasPermission('medcol6.statistics.view'))
                                        <a href="{{ route('medcol6.statistics') }}" class="list-group-item list-group-item-action border-0 rounded mb-1 py-2" style="background: rgba(248,249,250,0.8);">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 mr-3">
                                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                        <i class="fas fa-chart-bar text-white fa-sm"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 font-weight-bold text-dark">Estadísticas e Indicadores</h6>
                                                    <small class="text-muted">Análisis de pendientes por facturar</small>
                                                </div>
                                                <span class="badge badge-primary">NUEVO</span>
                                            </div>
                                        </a>
                                        @endif

                                        <a href="{{ route('smart.pendi') }}" class="list-group-item list-group-item-action border-0 rounded mb-1 py-2" style="background: rgba(248,249,250,0.8);">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 mr-3">
                                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                        <i class="fas fa-brain text-white fa-sm"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 font-weight-bold text-dark">Smart Pendi</h6>
                                                    <small class="text-muted">Análisis inteligente de pendientes</small>
                                                </div>
                                                <span class="badge badge-primary">SMART</span>
                                            </div>
                                        </a>

                                        @if(Auth::user()->hasPermission('analisis-nt.view'))
                                        <a href="{{ route('analisis-nt.index') }}" class="list-group-item list-group-item-action border-0 rounded mb-1 py-2" style="background: rgba(248,249,250,0.8);">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 mr-3">
                                                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                        <i class="fas fa-chart-bar text-white fa-sm"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 font-weight-bold text-dark">Análisis NT</h6>
                                                    <small class="text-muted">Medicamentos por contrato/nota técnica</small>
                                                </div>
                                                <span class="badge badge-info">NUEVO</span>
                                            </div>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accesos Rápidos - Solo para roles con permisos específicos -->
            @if(Auth::user()->hasRole('administrador') || Auth::user()->hasRole('super-administrador'))
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-tools mr-2"></i>
                            Herramientas Administrativas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @if(Auth::user()->hasPermission('usuarios.view'))
                            <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-users text-primary mr-2"></i>
                                Gestión de Usuarios
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('roles.view'))
                            <a href="{{ route('roles.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-user-tag text-success mr-2"></i>
                                Gestión de Roles
                            </a>
                            @endif

                            @if(Auth::user()->hasPermission('permisos.view'))
                            <a href="{{ route('permissions.index') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-shield-alt text-warning mr-2"></i>
                                Gestión de Permisos
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Card para usuarios de rol 3 (Auxiliar) -->
            @if(Auth::user()->rol == '3')
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-capsules mr-2"></i>
                            MedCol Consolidado
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <a href="{{ route('medcolCli.pendientes') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-share-square text-primary mr-2"></i>
                                Consultar Pendientes Medcol
                                <span class="badge badge-primary float-right">Consolidado</span>
                            </a>
                            <a href="{{ route('medcolCli.dispensado') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-share-square text-secondary mr-2"></i>
                                Consultar Dispensado Medcol
                                <span class="badge badge-secondary float-right">Consolidado</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
