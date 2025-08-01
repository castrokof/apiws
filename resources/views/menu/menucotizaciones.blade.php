<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MEDCOL COTIZACIONES</title>

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
            background-color: #2c3e50;
            color: #ecf0f1;
            font-family: 'Nunito', sans-serif;
        }
        .navbar-logo {
            display: inline-block;
            vertical-align: middle;
            width: 180px;
        }

        .navbar-logo img {
            display: block;
            width: 100%;
            height: 100%;
        }


        .loader1 {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url(./assets/lte/dist/img/loader.gif) 50% 50% no-repeat rgb(249, 249, 249);

            opacity: 8;
        }
        
               /* <!-- Añadir el CSS para soportar submenús --> */

      .dropdown-submenu {
            position: relative;
        }
        
        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -6px;
            margin-left: 0;
            display: none; /* Ocultar por defecto */
        }
        
        .dropdown-submenu:hover .dropdown-menu,
        .dropdown-submenu.show .dropdown-menu {
            display: block; /* Mostrar al hacer hover o cuando tenga la clase 'show' */
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }

        .navbar-dark .navbar-nav .nav-link:hover {
            color: rgba(255, 255, 255, 1);
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-lg">
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

                    @elseif(Auth::user()->rol == '2'|| Auth::user()->rol == '4')
                    <nav class="navbar navbar-expand-lg navbar-dark bg-danger rounded-lg">
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
                    <nav class="navbar navbar-expand-lg navbar-dark bg-danger rounded-lg">
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
                    <nav class="navbar navbar-expand-lg navbar-dark bg-danger rounded-lg">
                        <!-- <a class="navbar-brand" href="#">Aplicación</a> -->
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            
                           
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
            

            <div class="card bg-light">
                <div class="card-body text-center text-dark">
                    <div class="card-header"> <i class="fas fa-chart-line"></i> MedCol San Fernando</div>
                    <div class="list-group">


                        <a href="{{ route('medcol2.listascotizaciones') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-share-square"></i>Cotizaciones San Fernando <span class="badge badge-pill badge-primary pull-right">Salud Mental</span>
                        </a>

                    </div>
                </div>
            </div>
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-chart-line"></i> MedCol Dolor y Paliativos</div>
                    <div class="list-group">
                        <a href="{{ route('medcol3.listascotizaciones') }}" class="list-group-item list-group-item-action">
                            <i class="far fa-share-square"></i>Cotizaciones Dolor y Paliativos <span class="badge badge-pill badge-primary pull-right">Dolor y Paliativos</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="card bg-info text-dark">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-chart-line"></i> MedCol PCE-Huerfanas-Biologicos</div>
                    <div class="list-group">
                        <a href="{{ route('medcol4.listascotizaciones') }}" class="list-group-item list-group-item-action">
                            <i class="far fa-share-square"></i> Cotizaciones PCE-Huerfanas-Biologicos <span class="badge badge-pill badge-primary pull-right">PCE-HUE-BIO</span>
                        </a>
                    </div>
                </div>
            </div>

            @elseif(Auth::user()->rol == '3')
            <div class="card bg-light">
                <div class="card-body text-center">
                    <div class="card-header"> <i class="fas fa-chart-line"></i>MedCol Consolidado de Cotizaciones</div>
                    <div class="list-group">


                        <a href="{{ route('medcol3.dispensado') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-share-square"></i> Consultar Cotizaciones Medcol <span class="badge badge-pill badge-primary pull-right">Consolidado Medcol</span>
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