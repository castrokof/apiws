<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MEDCOL SW</title>

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