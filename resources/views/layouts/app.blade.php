<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

 <!-- <title>{{ config('app.name', 'Tempus SW') }}</title>  -->
    <title>Medcol SW</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset("assets/lte/plugins/fontawesome-free/css/all.min.css")}}">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
 <!-- Data tables -->

<!-- Theme style -->
<link rel="stylesheet" href="{{asset("assets/lte/dist/css/adminlte.min.css")}}">

<!-- Theme Toastr -->

<!-- Theme Toastr -->



<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">-->
<link rel="stylesheet" href="{{asset("assets/lte/plugins/toastr/toastr.min.css")}}">
   
@yield("styles")
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

 <style>
 
 .navbar-logo{
  display: inline-block;
  vertical-align: middle;
  width: 180px;
}

.navbar-logo img{
  display: block;
  width: 100%;
  height: 100%;
}
 
 
 .loader1{
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background: url(/public_apiws/assets/lte/dist/img/loader.gif) 50% 50% no-repeat rgb(249,249,249);
  
  opacity: 8;  
}

 </style>
</head>
<body>
    <div class="loader1"></div> 
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-logo" href="{{ url('/') }}">
                    <img src="{{asset("assets/lte/dist/img/iconmedcol.png")}}" alt="medcol_logo_header"  style="top: 12px">
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
                      <!--  @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                        @endif-->
                        
                        @else
                        
                        @if(Auth::user()->rol == '1')
                        <!--<div class="collapse navbar-collapse" id="navbarNavDropdown">-->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/home') }}">Direccionados</a>

                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="{{ url('/direccionado') }}">D. x documento</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="{{ url('/programado') }}">Programados</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="{{ url('/entregado') }}">Entregados</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="{{ url('/repentregado') }}">Reporte Entregados</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="{{ url('/facturado') }}">Facturado</a>
                              </li>
                        <!--</div>-->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="btn btn-secondary dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                   Rol-> {{ Auth::user()->rol }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                     <a class="dropdown-item" href="{{ route('tokenhercules') }}">
                                    {{ __('Mipres 2.0') }}
                                    </a>
                                     <a class="dropdown-item" href="{{ route('submenu') }}">
                                    {{ __('Pendientes') }}
                                     </a>
                                      <a class="dropdown-item" href="{{ route('dismenu') }}">
                                    {{ __('Dispensado') }}
                                     </a>
                                     <a class="dropdown-item" href="{{ route('register') }}">
                                    {{ __('Crear Usuario') }}
                                     </a>
                                     <a class="dropdown-item" href="{{ route('usuariosapiws') }}">
                                    {{ __('Listar Usuarios') }}
                                     </a>
                                     
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @elseif(Auth::user()->rol == '2')
                        <!--<div class="collapse navbar-collapse" id="navbarNavDropdown">-->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/home') }}">Direccionados</a>

                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="{{ url('/direccionado') }}">D. x documento</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="{{ url('/programado') }}">Programados</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="{{ url('/entregado') }}">Entregados</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="{{ url('/repentregado') }}">Reporte Entregados</a>
                              </li>
                              
                        <!--</div>-->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="btn btn-secondary dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                     <a class="dropdown-item" href="{{ route('tokenhercules') }}">
                                    {{ __('Mipres 2.0') }}
                                    </a>
                                     <a class="dropdown-item" href="{{ route('submenu') }}">
                                    {{ __('Sub Menu') }}
                                     </a>
                                       <a class="dropdown-item" href="{{ route('dismenu') }}">
                                    {{ __('Dispensado') }}
                                     </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @elseif(Auth::user()->rol == '3' )
                            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                              
                            
                            </div>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="btn btn-secondary dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    
                                     <a class="dropdown-item" href="{{ route('submenu') }}">
                                    {{ __('Pendientes') }}
                                     </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                             @elseif(Auth::user()->rol == '2')
                            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                              
                              <li class="nav-item">
                                   <a class="nav-link" href="{{ url('/home') }}">Direccionados</a>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="{{ url('/direccionado') }}">D. x documento</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="{{ url('/programado') }}">Programados</a>
                              </li>
                            
                            </div>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="btn btn-secondary dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                     <a class="dropdown-item" href="{{ route('tokenhercules') }}">
                                    {{ __('Mipres 2.0') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            
                             @endif
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
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
<!-- Jq Sweet alert cdn -->
<script src="{{asset("assets/lte/plugins/sweetalert2/sweetalert2.all.min.js")}}"></script>
<script src="{{asset("assets/js/jquery-select2/select2.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/moment/moment.min.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>

<!-- Jq Validate -->

<script src="{{asset("assets/js/jquery-validation/jquery.validate.min.js")}}"></script>
<script src="{{asset("assets/js/jquery-validation/localization/messages_es.min.js")}}"></script>
<script src="{{asset("assets/js/funciones.js")}}"></script>
<script src="{{asset("assets/js/scripts.js")}}"></script>   
<script type="text/javascript">
  
$(window).on("load",function() {
    $(".loader1").fadeOut("slow");
});

</script>
{{-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script> --}}
@yield("scriptsPlugins")
</body> 
</html>
