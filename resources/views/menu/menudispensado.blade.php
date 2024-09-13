<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MEDCOL DISPENSADOS</title>

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
        /* Fondo y tipografía */
        body {
            background-color: #f7f9fb; /* Fondo gris claro */
            color: #2d3436; /* Texto gris oscuro */
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 0;
        }
        
        /* Barra de navegación */
        .navbar {
            background-color: #2ecc71; /* Verde vivo para la barra de navegación */
            /*padding: 10px;*/
            border-bottom: 2px solid #62fcc4; /* Acento en verde claro */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Logo */
        .navbar-logo {
            display: inline-block;
            vertical-align: middle;
            width: 180px;
        }
        
        .navbar-logo img {
            display: block;
            width: 100%;
            height: auto; /* Mantiene la proporción de la imagen */
        }
        
        /* Enlaces de navegación */
        .navbar .nav-link {
            color: #ffffff; /* Blanco para los enlaces */
            padding: 15px;
            text-transform: uppercase;
            font-weight: 700;
            border-radius: 30px;
            transition: all 0.3s ease-in-out;
            border: 2px solid transparent; /* Sin borde por defecto */
        }
        
        /* Efecto hover para simular botones */
        .navbar .nav-link:hover {
            background-color: #34495e; /* Azul oscuro elegante */
            color: #1abc9c; /* Verde menta para el texto */
            border: 2px solid #34495e;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        }

        
        /* Efecto de elevación en hover */
        .navbar .nav-link:hover {
            transform: translateY(-3px);
        }
        
        /* Submenús */
        .dropdown-submenu {
            position: relative;
        }
        
        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -6px;
            margin-left: 0;
            display: none;
            background-color: #ffffff; /* Fondo blanco */
            border: 1px solid #62fcc4; /* Verde claro */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Mostrar submenú al pasar el cursor */
        .dropdown-submenu:hover .dropdown-menu,
        .dropdown-submenu.show .dropdown-menu {
            display: block;
        }
        
        /* Elementos dentro del submenú */
        .dropdown-menu .dropdown-item {
            color: #2d3436; /* Gris oscuro */
            padding: 10px 20px;
            font-size: 14px;
        }
        
        .dropdown-menu .dropdown-item:hover {
            background-color: #5df59d; /* Verde vivo en hover */
            color: #ffffff; /* Texto blanco en hover */
        }
        
        /* Botones */
        .btn {
            background-color: #6c5ce7; /* Lavanda para los botones */
            color: #fff;
            border-radius: 4px;
            padding: 10px 20px;
            font-weight: 600;
            border: none;
        }
        
        .btn:hover {
            background-color: #a29bfe; /* Tono más claro de lavanda en hover */
            color: #fff;
        }
        
        /* Loader */
        .loader1 {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url(./assets/lte/dist/img/loader.gif) 50% 50% no-repeat rgba(249, 249, 249, 0.9); /* Fondo suave */
        }
        
        /* Modal */
        #modal-header h5 {
            color: #6c5ce7; /* Lavanda oscuro para los títulos */
            font-weight: bold;
        }
        
        #modal-body {
            background-color: #ffffff; /* Fondo blanco */
            color: #2d3436; /* Texto gris oscuro */
        }
        
        /* Tablas */
        table {
            color: #2d3436;
            background-color: #ffffff;
        }
        
        th {
            background-color: #62fcc4; /* Verde claro en encabezados */
            color: #2d3436;
        }
        
        td {
            background-color: #f7f9fb; /* Fondo gris suave en celdas */
        }
        
        tr:hover {
            background-color: #dfe6e9; /* Gris claro en hover */
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

                    @if(Auth::user()->rol == '1')
                    <nav class="navbar navbar-expand-lg navbar-dark bg-success rounded-lg">
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
                                        <a class="dropdown-item text-dark" href="{{ route('tokenhercules') }}">{{ __('Mipres 2.0') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('submenu') }}">{{ __('Pendientes') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('dismenu') }}">{{ __('Dispensado') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('register') }}">{{ __('Crear Usuario') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('listasIndex') }}">{{ __('Crear listas') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('documentos') }}">{{ __('Crear documento') }}</a>
                                        <div class="dropdown-divider"></div>

                                            <!-- Submenú -->
                                            <div class="dropdown-submenu">
                                                <a class="dropdown-item dropdown-toggle text-dark" href="#">{{ __('Compras') }}</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item text-dark" href="{{ route('menucotizaciones') }}">{{ __('Cotizaciones') }}</a>
                                                    <a class="dropdown-item text-dark" href="{{ route('comprmenu') }}">{{ __('Ordenes de Compra') }}</a>
                                                </div>
                                            </div>
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
                    @elseif(Auth::user()->rol == '2' )
                    <nav class="navbar navbar-expand-lg navbar-dark bg-success rounded-lg">
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
                                        
                                        <a class="dropdown-item text-dark" href="{{ route('tokenhercules') }}">{{ __('Mipres 2.0') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('submenu') }}">{{ __('Pendientes') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('dismenu') }}">{{ __('Dispensado') }}</a>
                                        <div class="dropdown-divider"></div>

                                            <!-- Submenú -->
                                            <div class="dropdown-submenu">
                                                <a class="dropdown-item dropdown-toggle text-dark" href="#">{{ __('Compras') }}</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item text-dark" href="{{ route('menucotizaciones') }}">{{ __('Cotizaciones') }}</a>
                                                    <a class="dropdown-item text-dark" href="{{ route('comprmenu') }}">{{ __('Ordenes de Compra') }}</a>
                                                </div>
                                            </div>
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
                    <nav class="navbar navbar-expand-lg navbar-dark bg-success rounded-lg">
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
                                        <a class="dropdown-item text-dark" href="{{ route('tokenhercules') }}">{{ __('Mipres 2.0') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('submenu') }}">{{ __('Pendientes') }}</a>
                                        <a class="dropdown-item text-dark" href="{{ route('dismenu') }}">{{ __('Dispensado') }}</a>
                                        <div class="dropdown-divider"></div>

                                            <!-- Submenú -->
                                            <div class="dropdown-submenu">
                                                <a class="dropdown-item dropdown-toggle text-dark" href="#">{{ __('Compras') }}</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item text-dark" href="{{ route('menucotizaciones') }}">{{ __('Cotizaciones') }}</a>
                                                    <a class="dropdown-item text-dark" href="{{ route('comprmenu') }}">{{ __('Ordenes de Compra') }}</a>
                                                </div>
                                            </div>
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
                    @elseif(Auth::user()->rol == '3' )
                    <nav class="navbar navbar-expand-lg navbar-dark bg-success rounded-lg">
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

            <div class="card bg-light">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-capsules"></i>MedCol San Fernando</div>
                    <div class="list-group">
                        <a href="{{ route('medcol2.dispensado') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-share-square"></i> Gestionar Dispensación de San Fernando <span class="badge badge-pill badge-primary pull-right">Salud Mental</span>
                        </a>

                    </div>
                </div>
            </div>
            <div class="card bg-warning">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-capsules"></i>MedCol Dolor y Paliativos</div>
                    <div class="list-group">
                        <a href="{{ route('medcold.dispensado') }}" class="list-group-item list-group-item-action">
                            <i class="far fa-share-square"></i> Gestionar Dispensación de Dolor y Paliativos <span class="badge badge-pill badge-primary pull-right">Dolor y Paliativos</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card bg-info">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-capsules"></i>MedCol PCE-Huerfanas-Biologicos</div>
                    <div class="list-group">
                        <a href="{{ route('medcol3.dispensado') }}" class="list-group-item list-group-item-action">
                            <i class="far fa-share-square"></i> Gestionar Dispensación de PCE-Huerfanas-Biologicos <span class="badge badge-pill badge-primary pull-right">PCE-HUE-BIO</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card bg-danger">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-capsules"></i>MedCol EMCALI</div>
                    <div class="list-group">
                        <a href="{{ route('medcol5.dispensado') }}" class="list-group-item list-group-item-action">
                            <i class="far fa-share-square"></i> Gestionar Dispensación de EMCALI <span class="badge badge-pill badge-primary pull-right">EMCALI</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card bg-success">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-capsules"></i>MedCol SOS - JAMUNDI</div>
                    <div class="list-group">
                        <a href="{{ route('medcol5.dispensado') }}" class="list-group-item list-group-item-action">
                            <i class="far fa-share-square"></i> Gestionar Dispensación <span class="badge badge-pill badge-primary pull-right">SOS - JAMUNDI</span>
                        </a>
                    </div>
                </div>
            </div>

            @elseif(Auth::user()->rol == '3')
            <div class="card bg-light">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-capsules"></i>MedCol Consolidado de pendientes</div>
                    <div class="list-group">
                        <a href="{{ route('medcol3.dispensado') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-share-square"></i> Consultar Dispensación Medcol <span class="badge badge-pill badge-primary pull-right">consolidado Medcol</span>
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