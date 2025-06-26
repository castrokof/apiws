@extends('layouts.app')

@section('titulo')
Detalle Scann
@endsection

@section("styles")
<link href="{{ asset("assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css") }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset("assets/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css") }}" rel="stylesheet" type="text/css"/>
<script src="{{ asset('assets/lte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<style>


.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
}

.carousel {
    margin-bottom: 20px;
}
</style>
@endsection

@section('content')
@include('scann.modal.modalScannApi')
@include('scann.modal.modalScannApiVerFotos')
<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')

        <div class="card card-info">
            <div class="card-header with-border">
                <h3 class="card-title">Comprobantes</h3>
                <div class="card-tools pull-right">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Atrás
                    </a>
                </div>
            </div>

            <div class="card-body p-3">
                <div class="row text-center">
                    <div class="col-md-4">
                        <h5>Ordenes</h5>
                        <div id="carousel-ordenes" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner" id="contenedor-ordenes"></div>
                            <a class="carousel-control-prev" href="#carousel-ordenes" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#carousel-ordenes" role="button" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5>Comprobantes</h5>
                        <div id="carousel-comprobantes" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner" id="contenedor-comprobantes"></div>
                            <a class="carousel-control-prev" href="#carousel-comprobantes" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#carousel-comprobantes" role="button" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 pdf-col">
                        <h5>PDFS</h5>
                        <div id="contenedor-pdfs"></div>
                        <!-- Puedes agregar funcionalidad similar para PDFs aquí -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   $(document).ready(function () {
    const idFromUrl = window.location.pathname.split('/').pop();

    $.ajax({
        url: "{{ route('indexDetalleScann') }}",
        type: "GET",
        data: { id: idFromUrl },
        success: function (response) {
            console.log(response);
            response.data.forEach(item => {
                renderImagenes(item.orden_urls, 'ordenes');
                renderImagenes(item.comprobante_urls, 'comprobantes');
                renderImagenes(item.pdf_urls,'pdfs');
                // Si hay PDFS: renderPDFs(item.pdf_urls);
            });
        },
        error: function () {
            alert("Error al cargar los datos.");
        }
    });
});

function renderImagenes(urls, tipo) {
    const contenedor = $(`#contenedor-${tipo}`);
    contenedor.empty(); // Limpiar antes

    let esPDF = urls.some(url => url.toLowerCase().endsWith('.pdf'));

    if (esPDF) {
        // Mostrar solo la columna de PDFs
        $('.col-md-4').hide(); // Oculta todas
        const pdfCol = $('.pdf-col');
        pdfCol.show();
        pdfCol.removeClass('col-md-4').addClass('col-md-6 offset-md-3'); // Centrar
        $('#contenedor-pdfs').closest('.col-md-4').show(); // Muestra solo PDF
        $('#contenedor-pdfs').empty(); // Limpiar contenedor de PDFs
    }

    urls.forEach((url, index) => {
        const archivo = url.replace(/^.*[\\\/]/, '');
        const extension = archivo.split('.').pop().toLowerCase();
        let carpeta = url.includes("comprobantes") ? "comprobantes" : "ordenes";

        if (extension === 'pdf') {
            carpeta = "escaner/temp";
        }

        const targetContenedor = extension === 'pdf' ? $('#contenedor-pdfs') : contenedor;

        let slide = $(`
            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                <div class="d-flex justify-content-center align-items-center" style="height: 400px;">
                    <i class="fa fa-spinner fa-spin fa-2x text-secondary"></i>
                </div>
            </div>
        `);

        targetContenedor.append(slide);

        $.ajax({
            url: "{{ route('mover.imagen') }}",
            type: "POST",
            data: {
                imagen: archivo,
                carpeta: carpeta,
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                if (response.url) {
                    if (extension === 'pdf') {
                        slide.html(`
                            <iframe src="${response.url}" width="100%" height="700px" class="border rounded shadow-sm">
                                Este navegador no soporta la visualización de PDFs. <a href="${response.url}" target="_blank">Descargar PDF</a>.
                            </iframe>
                        `);
                    } else {
                        slide.html(`
                            <img src="${response.url}" class="d-block w-100 img-thumbnail" alt="Imagen">
                        `);
                    }
                } else {
                    slide.html('<span class="text-danger">Error al cargar recurso</span>');
                }
            },
            error: function () {
                slide.html('<span class="text-danger">Error al mover archivo</span>');
            }
        });
    });
}



function renderPDFs(urls) {
    const contenedorPDFs = $('#contenedor-pdfs');
    contenedorPDFs.empty(); // Limpia antes de agregar nuevos
     

    urls.forEach((url, index) => {
        const viewer = `
            <div class="mb-3">
                <iframe src="${url}" width="100%" height="400px" class="border rounded shadow-sm">
                    Este navegador no soporta la visualización de PDFs. <a href="${url}" target="_blank">Descargar PDF</a>.
                </iframe>
            </div>
        `;
        contenedorPDFs.append(viewer);
    });
}

    
    $(document).on('click', '.mover-imagen-detalle', function(e) {
    e.preventDefault();

    let boton = $(this);
    let imagen = boton.data('imagen');
    let carpeta = boton.data('carpeta');

    $.ajax({
        url: "{{ route('mover.imagen') }}",
        type: "POST",
        data: {
            imagen: imagen,
            carpeta: carpeta,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response.url) {
                // Reemplazar el ícono por la miniatura directamente
                boton.replaceWith(`
                    <img src="${response.url}" 
                         class="img-thumbnail" 
                         style="width: 340px; height: auto;" 
                         alt="Imagen">
                `);
            }
        },
        error: function() {
            alert("Error al mover la imagen.");
        }
    });
});
    
    var idioma_espanol =
                 {
                "sProcessing":     "Procesando...",
                "sLengthMenu":     "Mostrar _MENU_ registros",
                "sZeroRecords":    "No se encontraron resultados",
                "sEmptyTable":     "Ningún dato disponible en esta tabla =(",
                "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix":    "",
                "sSearch":         "Buscar:",
                "sUrl":            "",
                "sInfoThousands":  ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst":    "Primero",
                    "sLast":     "Último",
                    "sNext":     "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Visibilidad"
                }
                }
   
    
</script>
@endsection

