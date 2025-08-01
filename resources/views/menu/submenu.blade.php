<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PENDIENTES</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/lte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/lte/dist/css/adminlte.min.css') }}">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f9;
            /* Un gris claro para un aspecto limpio */
            color: #2c3e50;
            /* Gris oscuro para buen contraste */
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #ffffff;
            /* Blanco elegante para la barra de navegación */
            border-bottom: 2px solid #a29bfe;
            /* Acento en lavanda claro */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            /* Sombra suave para dar profundidad */
        }

        .navbar-logo {
            display: inline-block;
            vertical-align: middle;
            width: 180px;
        }

        .navbar-logo img {
            display: block;
            width: 100%;
            height: auto;
            /* Mantiene la proporción de la imagen */
        }

        .navbar .nav-link {
            color: #2c3e50;
            /* Gris oscuro para los enlaces */
            padding: 15px;
            text-transform: uppercase;
            font-weight: 700;
            border-radius: 30px;
            transition: all 0.3s ease-in-out;
            border: 2px solid transparent;
            /* Sin borde por defecto */
        }

        /* Efecto hover para simular botones */
        .navbar .nav-link:hover {
            background-color: #62fcc4;
            color: #1e272e;
            border: 2px solid #62fcc4;
            /* Aparece borde en hover */
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
            /* Sombra suave */
        }

        /* Efecto de sobreimpresión al pasar el cursor (como si "salieran") */
        .navbar .nav-link:hover {
            transform: translateY(-3px);
            /* Se eleva ligeramente al hacer hover */
        }

        .loader1 {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url(./assets/lte/dist/img/loader.gif) 50% 50% no-repeat rgba(244, 247, 249, 0.9);
            /* Fondo claro semitransparente */
        }

        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -6px;
            margin-left: 0;
            display: none;
            /* Ocultar por defecto */
            background-color: #ffffff;
            /* Fondo blanco */
            border: 1px solid #74b9ff;
            /* Borde azul claro */
        }

        .dropdown-submenu:hover .dropdown-menu,
        .dropdown-submenu.show .dropdown-menu {
            display: block;
            /* Mostrar al hacer hover o cuando tenga la clase 'show' */
        }

        .dropdown-menu {
            background-color: #ffffff;
            /* Fondo blanco para los menús desplegables */
            border: 1px solid #a29bfe;
            /* Borde lavanda */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Sombra suave */
        }

        .dropdown-menu .dropdown-item {
            color: #2c3e50;
            /* Texto gris oscuro */
            padding: 10px 20px;
            font-size: 14px;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #74b9ff;
            /* Fondo azul claro en hover */
            color: #ffffff;
            /* Texto blanco en hover */
        }

        /* Botones o enlaces */
        .btn {
            background-color: #a29bfe;
            /* Fondo lavanda para botones */
            color: #fff;
            border-radius: 4px;
            padding: 10px 20px;
            font-weight: 600;
            border: none;
        }

        .btn:hover {
            background-color: #6c5ce7;
            /* Un tono más oscuro de lavanda en hover */
            color: #fff;
        }

        /* Encabezados dentro del modal */
        #modal-header h5 {
            color: #6c5ce7;
            /* Color lavanda oscuro para los títulos del modal */
            font-weight: bold;
        }

        #modal-body {
            background-color: #ffffff;
            /* Fondo blanco para el cuerpo del modal */
            color: #2c3e50;
            /* Texto gris oscuro */
        }

        /* Mejoras generales */
        table {
            color: #2c3e50;
            /* Texto gris oscuro en las tablas */
            background-color: #ffffff;
            /* Fondo blanco en las tablas */
        }

        th {
            background-color: #a29bfe;
            /* Fondo lavanda claro en los encabezados */
            color: #2c3e50;
            /* Texto gris oscuro */
        }

        td {
            background-color: #f4f7f9;
            /* Fondo gris claro en las celdas */
        }

        tr:hover {
            background-color: #74b9ff;
            /* Fondo azul claro en hover */
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-logo" href="{{ url('/') }}">
                <img src="{{asset("assets/lte/dist/img/iconmedcol.png")}}" alt="medcol_logo_header" style="top: 12px">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>


                    @else

                    @if(Auth::user()->rol == '1' || Auth::user()->rol == '5' || Auth::user()->rol == '6')

                    {{-- ROL 1: ADMINISTRADOR (Menú completo) --}}
                    @if(Auth::user()->rol == '1')
                    <nav class="navbar navbar-expand-lg navbar-dark bg-info rounded-lg">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            {{-- Menú principal izquierdo para Rol 1 --}}
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ url('/usuariosapi') }}">Usuarios API</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('medcolCli.pendientes') }}">Pendientes Medcol</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('medcolCli.dispensado') }}">Dispensado Medcol</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('indexscann') }}">Scann documento</a>
                                </li>
                            </ul>
                            {{-- Menú desplegable derecho para Rol 1 --}}
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right rounded-lg" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item text-dark" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <a class="dropdown-item text-dark" href="{{ route('tokenhercules') }}">{{ __('Mipres 2.0') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('submenu') }}">{{ __('Pendientes') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('dismenu') }}">{{ __('Dispensado') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('register') }}">{{ __('Crear Usuario') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('listasIndex') }}">{{ __('Crear listas') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('documentos') }}">{{ __('Crear documento') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('indexscann') }}">{{ __('Scann documento') }}</a>

                                        <div class="dropdown-divider"></div>

                                        <!-- Submenú Compras -->
                                        <div class="dropdown-submenu">
                                            <a class="dropdown-item dropdown-toggle text-dark" href="#">{{ __('Compras') }}</a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-dark" href="{{ route('menucotizaciones') }}">{{ __('Cotizaciones') }}</a>
                                                <a class="dropdown-item text-dark" href="{{ route('compras.medcol3') }}">{{ __('Ordenes de Compra') }}</a>
                                            </div>
                                        </div>
                                        <!-- Submenú SOS -->
                                        <div class="dropdown-submenu">
                                            <a class="dropdown-item dropdown-toggle text-dark" href="#">{{ __('SOS') }}</a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-dark" href="{{ route('indexsos') }}">{{ __('Validar Derechos') }}</a>
                                                <a class="dropdown-item text-dark" href="{{ route('indexformulas') }}">{{ __('Formulas SOS') }}</a>
                                            </div>
                                        </div>
                                        <!-- Submenú Scann -->
                                        <div class="dropdown-submenu">
                                            <a class="dropdown-item dropdown-toggle text-dark" href="#">{{ __('Scann') }}</a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-dark" href="{{ route('indexscannpdfs') }}">{{ __('Generar PDFS') }}</a>
                                            </div>
                                        </div>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>

                    {{-- ROLES 5 y 6: COMPRAS (Menú restringido) --}}
                    @elseif(Auth::user()->rol == '5' || Auth::user()->rol == '6')
                    <nav class="navbar navbar-expand-lg navbar-dark bg-info rounded-lg">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            {{-- Menú principal izquierdo (vacío para estos roles) --}}
                            <ul class="navbar-nav mr-auto">
                            </ul>

                            {{-- Menú desplegable derecho para Roles 5 y 6 --}}
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right rounded-lg" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item text-dark" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <div class="dropdown-divider"></div>

                                        <!-- Submenú Compras -->
                                        <div class="dropdown-submenu">
                                            <a class="dropdown-item dropdown-toggle text-dark" href="#">{{ __('Compras') }}</a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-dark" href="{{ route('menucotizaciones') }}">{{ __('Cotizaciones') }}</a>
                                                <a class="dropdown-item text-dark" href="{{ route('compras.medcol3') }}">{{ __('Ordenes de Compra') }}</a>
                                            </div>
                                        </div>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    @endif


                    @elseif(Auth::user()->rol == '2' || Auth::user()->rol == '4' )
                    <nav class="navbar navbar-expand-lg navbar-dark bg-info rounded-lg">
                        <!-- <a class="navbar-brand" href="#">Aplicación</a> -->
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ url('/usuariosapi') }}">Usuarios API</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('medcolCli.pendientes') }}">Pendientes Medcol</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('medcolCli.dispensado') }}">Dispensado Medcol</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('indexscann') }}">Scann documento</a>
                                </li>
                            </ul>
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right rounded-lg" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item text-dark" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <!-- <a class="dropdown-item text-dark" href="{{ route('comprmenu') }}">{{ __('Compras') }}</a> -->
                                        <a class="dropdown-item text-dark" href="{{ route('tokenhercules') }}">{{ __('Mipres 2.0') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('submenu') }}">{{ __('Pendientes') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('dismenu') }}">{{ __('Dispensado') }}</a>
                                        <div class="dropdown-divider"></div>

                                        <!-- Submenú -->
                                        @if(Auth::user()->email == 'gerenteoperacioneseinnovacion@saludmedcol.com'){
                                        <div class="dropdown-submenu">
                                            <a class="dropdown-item dropdown-toggle text-dark" href="#">{{ __('Compras') }}</a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-dark" href="{{ route('menucotizaciones') }}">{{ __('Cotizaciones') }}</a>
                                                <a class="dropdown-item text-dark" href="{{ route('comprmenu') }}">{{ __('Ordenes de Compra') }}</a>
                                            </div>
                                        </div>
                                        }
                                        @endif
                                        <!-- Submenú -->
                                        <div class="dropdown-submenu">
                                            <a class="dropdown-item dropdown-toggle text-dark" href="#">{{ __('SOS') }}</a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item text-dark" href="{{ route('indexsos') }}">{{ __('Validar Derechos') }}</a>
                                                <a class="dropdown-item text-dark" href="{{ route('indexformulas') }}">{{ __('Formulas SOS') }}</a>
                                            </div>
                                        </div>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    @elseif(Auth::user()->email == 'luzcve@hotmail.com')
                    <nav class="navbar navbar-expand-lg navbar-dark bg-info rounded-lg">
                        <!-- <a class="navbar-brand" href="#">Aplicación</a> -->
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ url('/usuariosapi') }}">Usuarios API</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('medcolCli.pendientes') }}">Pendientes Medcol</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('medcolCli.dispensado') }}">Dispensado Medcol</a>
                                </li>
                            </ul>
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right rounded-lg" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item text-dark" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <a class="dropdown-item text-dark" href="{{ route('comprmenu') }}">{{ __('Compras') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('tokenhercules') }}">{{ __('Mipres 2.0') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('submenu') }}">{{ __('Pendientes') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('dismenu') }}">{{ __('Dispensado') }}</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                    @elseif(Auth::user()->rol == '3' )
                    <nav class="navbar navbar-expand-lg navbar-dark bg-info rounded-lg">
                        <!-- <a class="navbar-brand" href="#">Aplicación</a> -->
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <!-- <ul class="navbar-nav mr-auto">
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ url('/usuariosapi') }}">Usuarios API</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('medcolCli.pendientes') }}">Pendientes Medcol</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="{{ route('medcolCli.dispensado') }}">Dispensado Medcol</a>
                                </li>
                            </ul> -->
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right rounded-lg" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item text-dark" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <a class="dropdown-item text-dark" href="{{ route('submenu') }}">{{ __('Pendientes') }}</a>
                                        <!-- <a class="dropdown-item text-dark" href="{{ route('dismenu') }}">{{ __('Dispensado') }}</a> -->
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>

                    @endif
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <div class="container p-3 pt-3">
        <div class="card-columns">

            @if (Route::has('login'))
            @auth
            @if(Auth::user()->rol == '1' || Auth::user()->rol == '2')
            <!-- <div class="card bg-primary">
                        <div class="card-body text-center">
                            <div class="card-header"> <i class="fas fa-prescription-bottle-alt"></i> Registrar token</div>
                            <div class="list-group">
                                <a href="{{ route('tokenhercules') }}" class="list-group-item list-group-item-action">
                                    <i class="fas fa-key"></i> Link token <span
                                        class="badge badge-pill badge-primary pull-right">Hercules</span>
                                </a>

                            </div>
                        </div>
                    </div> -->

            <!-- <div class="card bg-light">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-capsules"></i>MedCol San Fernando</div>
                    <div class="list-group">


                        <a href="{{ route('pendientes') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-share-square"></i> Gestionar Pendientes San Fernando <span class="badge badge-pill badge-primary pull-right">Salud Mental</span>
                        </a>

                    </div>
                </div>
            </div> -->
            <!-- <div class="card bg-warning">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-capsules"></i>MedCol Dolor y Paliativos</div>
                    <div class="list-group">
                        <a href="{{ route('medcold.pendientes') }}" class="list-group-item list-group-item-action">
                            <i class="far fa-share-square"></i> Gestionar Pendientes Dolor y Paliativos <span class="badge badge-pill badge-primary pull-right">Dolor y Paliativos</span>
                        </a>
                    </div>
                </div>
            </div> -->

            <!-- <div class="card bg-info">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-capsules"></i>MedCol PCE-Huerfanas-Biologicos</div>
                    <div class="list-group">
                        <a href="{{ route('medcol3.pendientes') }}" class="list-group-item list-group-item-action">
                            <i class="far fa-share-square"></i> Gestionar Pendientes PCE-Huerfanas-Biologicos <span class="badge badge-pill badge-primary pull-right">PCE-HUE-BIO</span>
                        </a>
                    </div>
                </div>
            </div> -->

            <!-- <div class="card bg-success">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-capsules"></i>MedCol EMCALI</div>
                    <div class="list-group">
                        <a href="{{ route('medcol5.pendientes') }}" class="list-group-item list-group-item-action">
                            <i class="far fa-share-square"></i> Gestionar Pendientes Farmacia Emcali <span class="badge badge-pill badge-primary pull-right">EMCALI</span>
                        </a>
                    </div>
                </div>
            </div> -->

            <!-- MedCol New Servidor - Diseño Moderno -->
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-0">
                    <!-- Header Principal -->
                    <div class="text-center py-4 text-white">
                        <div class="mb-3">
                            <i class="fas fa-server fa-3x mb-2" style="color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></i>
                        </div>
                        <h4 class="font-weight-bold mb-1" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">MEDCOL API PENDIENTES</h4>
                        <p class="mb-0 small opacity-75">Sistema de Gestión Farmacéutica Avanzada</p>
                    </div>

                    <!-- Sección Principal - Destacada -->
                    <div class="px-4 pb-3">
                        <div class="card border-0 shadow-sm mb-3" style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px);">
                            <div class="card-body p-3">
                                <div class="text-center mb-2">
                                    <i class="fas fa-cogs fa-2x text-primary mb-2"></i>
                                    <h6 class="font-weight-bold text-dark mb-1">🚀 ACCESO PRINCIPAL</h6>
                                </div>
                                <a href="{{ route('medcol6.pendientes') }}" class="btn btn-primary btn-lg btn-block shadow-sm" style="border-radius: 15px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <i class="fas fa-capsules mr-2"></i>
                                    Gestionar Pendientes
                                    <!-- <span class="badge badge-light ml-2">NEW SRV</span> -->
                                </a>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @elseif(Auth::user()->rol == '3')
            <div class="card bg-light">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-capsules"></i>MedCol Consolidado de pendientes</div>
                    <div class="list-group">


                        <a href="{{ route('medcolCli.pendientes') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-share-square"></i> Consultar Pendientes Medcol <span class="badge badge-pill badge-primary pull-right">consolidado Medcol</span>
                        </a>

                    </div>
                </div>
            </div>
            <div class="card bg-primary">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-capsules"></i>MedCol Consolidado Dispensado</div>
                    <div class="list-group">


                        <a href="{{ route('medcolCli.dispensado') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-share-square"></i> Consultar Dispensaado Medcol <span class="badge badge-pill badge-secondary pull-right">consolidado Medcol</span>
                        </a>

                    </div>
                </div>
            </div>
            @endif

            <div class="top-right links">
                @else
                <div class="card bg-primary">
                    <div class="card-body text-center">
                        <img class="img-fluid" src="{{ asset('assets/lte/dist/img/iconmedcol.png') }}" alt="Medcol image">

                    </div>
                </div>
                <div class="card bg-primary">
                    <div class="card-body text-center">
                        <div class="card-header"> <i class="fas fa-prescription-bottle-alt"></i> Iniciar sesión</div>
                        <div class="list-group">
                            <a href="{{ route('login') }}" class="list-group-item list-group-item-action">
                                <i class="fas fa-key"></i> Link Ininiciar sesión <span class="badge badge-pill badge-primary pull-right">Login</span>
                            </a>

                        </div>
                    </div>
                </div>
                <div class="card bg-primary">
                    <div class="card-body text-center">
                        <img class="img-fluid" src="{{ asset('assets/lte/dist/img/iconmedcol.png') }}" alt="Medcol image">

                    </div>
                </div>


                @if (Route::has('register'))
                {{-- <a href="{{ route('register') }}">Register</a> --}}
                @endif

                @endauth
            </div>
            @endif

        </div>

    </div>

    <!-- jQuery -->
    <script src="{{asset("assets/lte/plugins/jquery/jquery.min.js")}}"></script>
    <!-- Bootstrap 4 -->

    <script src="{{asset("assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset("assets/lte/dist/js/adminlte.min.js")}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset("assets/lte/dist/js/demo.js")}}"></script>
    <!-- Jq Sweet alert cdn -->

    <!-- Jq Validate -->

    <script src="{{asset("assets/js/jquery-validation/jquery.validate.min.js")}}"></script>
    <script src="{{asset("assets/js/jquery-validation/localization/messages_es.min.js")}}"></script>
    <script src="{{asset("assets/js/funciones.js")}}"></script>
    <script src="{{asset("assets/js/scripts.js")}}"></script>
    <script type="text/javascript">
        $(window).on("load", function() {
            $(".loader1").fadeOut("slow");
        });
    </script>

</body>

</html>