@extends('layouts.app')
@section("styles")
<link href="{{asset("assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card col-l-12">
                <div class="card-header">{{ __('Medcol') }}</div>

                <div class="card-body">
                    @if (session("mensaje"))
                    <div class="alert alert-warning alert-dismissible" data-auto-dismiss="6000">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Mensaje Medcol</h5>
                        <li>{{ session("mensaje")}}</li>
                    </div>
                    @endif
      
                     
                    <form  id="formularioTokenHercules">
                    @csrf
                    @include('form-consulta-hercules')
                    
                    <button type="submit" id="consultar" class="btn btn-success">Agregar</button>
                    
                    </form>
                    


                </div>
               

                    

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section("scriptsPlugins")
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{asset("assets/lte/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
<script src="{{asset("assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>

<script>
     $(document).ready(function() {
        $('#formularioTokenHercules').submit(function(event) {
            event.preventDefault(); // Evita la submission tradicional del formulario

            var formData = $(this).serialize(); // Serializa los datos del formulario
            
             Swal.fire({
                    icon: "info",
                    title: 'Espere por favor !',
                    html: 'Conectando con el ministerio', // add html attribute if you want or remove
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading()
                    },
                }),


            $.ajax({
                url: "{{ route('tokenhercules1') }}", // La misma ruta que tenías en el formulario
                type: "POST",
                data: formData,
                dataType: 'json', // Esperamos una respuesta en formato JSON
                success: function(response) {
                    if (response.mensaje && response.token) {
                        Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        html: response.mensaje + '<br><strong>Token:</strong> ' + response.token,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => { // Se ejecuta después de que se cierra el primer modal
                        Swal.fire({
                            title: 'Cargando...',
                            html: 'Redirigiendo a la página Direccionados...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                                window.location.href = "{{ url('home') }}";
                            }
                        });
                    });
                    } else if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error!',
                            text: response.error
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la petición AJAX:", xhr, status, error);
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: 'Ocurrió un error al enviar la información. Por favor, intenta nuevamente.'
                    });
                }
            });
        });
    });

  
       
</script>
@endsection
