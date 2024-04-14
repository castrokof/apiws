<!DOCTYPE html>
<html>
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>@yield("titulo",'Medcol')</title>

    <!-- Favicons -->
    <link href="{{asset("assets/lte/dist/img/iconmedcol.png")}}" rel="icon">
    <link href="{{asset("assets/lte/dist/img/iconmedcol.png")}}" rel="fidem_icon">

  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset("assets/$theme/plugins/fontawesome-free/css/all.min.css")}}">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Data tables -->

  <!-- Theme style -->
  
  <link rel="stylesheet" href="{{asset("assets/$theme/dist/css/adminlte.min.css")}}">

   <!-- Theme sweetalert2 -->
   <link rel="stylesheet" href="{{asset("assets/$theme/plugins/sweetalert2/sweetalert2.min.css")}}">



  @yield("styles")

  <!-- Theme Custom -->
   <!--<link rel="stylesheet" href="{{asset("assets/css/custom.css")}}"> -->




  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">




</head>

<body>
<div class="loader1"></div>
@php
$iduser = Session()->get('usuario');
$id= Session()->get('usuario_id');
@endphp




    <div class="wrapper">
     <!---- Inicio Header ---->
     @include("theme/$theme/header")
     <!----   Fin Header  ---->
    

     <!-- Content py-4. Contains page content -->
          
     
          
        <div class="py-4"> 

           
            
            @yield('contenido')
         
          </div>
          <!--- Inicio Footer --->
         <!--- @include("theme/$theme/footer")--->
          <!--- Fin Footer --->
    </div>
<!-- jQuery -->
<script src="{{asset("assets/$theme/plugins/jquery/jquery.min.js")}}"></script>
<!-- data tables -->

<!-- Bootstrap 4 -->
<script src="{{asset("assets/$theme/plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
<!-- AdminLTE App -->
<script src="{{asset("assets/$theme/dist/js/adminlte.min.js")}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset("assets/$theme/dist/js/demo.js")}}"></script>

@yield("scriptsPlugins")

<script src="{{asset("assets/$theme/plugins/sweetalert2/sweetalert2.all.min.js")}}"></script>

<!-- Jq Sweet alert cdn -->
{{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -}}
{{-- <script src="{{asset("assets/$theme/plugins/sweetalert2/sweetalert2.min.js")}}"></script> --}}

<!-- Jq Toastr cdn -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>-->
<script src="{{asset("assets/$theme/plugins/toastr/toastr.min.js")}}"></script>
<!-- Jq Validate -->
<script src="{{asset("assets/js/jquery-validation/jquery.validate.min.js")}}"></script>
<script src="{{asset("assets/js/jquery-validation/localization/messages_es.min.js")}}"></script>
<script src="{{asset("assets/js/funciones.js")}}"></script>
<script src="{{asset("assets/js/scripts.js")}}"></script>
@yield("scripts")

<script src="{{asset("assets/pages/scripts/admin/usuario/crear.js")}}" type="text/javascript"></script>

<script type="text/javascript">

  $(window).on("load",function() {
      $(".loader1").fadeOut("fast");
  });
  </script>

<script>


  $(document).ready(function(){



      });






  </script>


  </body>
</html>
