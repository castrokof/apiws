@extends('layouts.app')

@section('titulo')
Scann api
@endsection
@section("styles")


<link href="{{asset("assets/lte/plugins/datatables-bs4/css/dataTables.bootstrap4.css")}}" rel="stylesheet" type="text/css"/>
<link href="{{asset("assets/lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css")}}" rel="stylesheet" type="text/css"/>

<style>
#modal_scann .modal-body {
    overflow: hidden;
    position: relative;
}

#videoStream {
    width: 100%;
    max-height: 50vh; /* Evita que ocupe toda la pantalla */
    object-fit: cover;
}

.btn-flotante {
    position: absolute;
    bottom: 10px;
    right: 10px;
    z-index: 9999;
}


</style>

@endsection



@section('content')

    @include('scann.tablas.tablaScannApi')
    @include('scann.modal.modalScannApi')
    @include('scann.modal.modalScannApiVerFotos')
 

@endsection

@section('scripts')
    <!--<script src="{{asset('assets/pages/scripts/admin/scann/index.js') }}"></script>-->
@endsection

@section("scriptsPlugins")

 
    <script src="{{asset("assets/js/jquery-select2/select2.min.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/lte/plugins/datatables/jquery.dataTables.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/lte/plugins/datatables-bs4/js/dataTables.bootstrap4.js")}}" type="text/javascript"></script>
    <script src="{{asset("assets/lte/plugins/datatables-responsive/js/dataTables.responsive.min.js")}}" type="text/javascript"></script>
    <script async src="https://docs.opencv.org/4.x/opencv.js"></script>
    




    <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tesseract.js/2.1.4/tesseract.min.js"></script>

<script>
    
    $(document).ready(function () {
    
let fotosComprobante = [];
let fotosOrden = [];
let streamActivo = null; // Variable global para almacenar el stream activo


           $(document).on('click', '.mover-imagen', function(e) {
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
                            const extension = response.url.split('.').pop().toLowerCase();
            
                            if (extension === 'pdf') {
                                // Mostrar PDF
                                $('#modalPdf').attr('src', response.url).show();
                                $('#modalImage').hide();
                            } else {
                                // Mostrar imagen
                                $('#modalImage').attr('src', response.url).show();
                                $('#modalPdf').hide();
                            }
            
                            $('#imageModal').modal('show');
                        }
                    },
                    error: function() {
                        alert("Error al mover la imagen o abrir el archivo.");
                    }
                });
            });
        

       // Funcion para pintar con data table
var datatable = $('#scann_api').DataTable({
            language: idioma_espanol,
            processing: true,
            lengthMenu: [ [25, 50, 100, 500, 4000, 7000, 10000, -1 ], [25, 50, 100, 500, 4000, 7000, 10000, "Mostrar Todo"] ],
            processing: true,
            serverSide: true,
            aaSorting: [[ 7, "desc" ]],
            ajax:{
              url:"{{route('indexscann')}}",
                 },
            columns: [
                        { data: 'acciones', name: 'acciones' },
                        {
                            data: 'id', // Suponiendo que 'id' es el identificador de la orden
                            name: 'detalle_orden',
                            orderable: false,
                            searchable: false,
                            render: function (data) {
                                return `https://manteliviano.com/public_apiws/detalleScann/${data}`;
                            }
                        },
                        { data: 'codigo', name: 'codigo' },
                        { 
                            data: 'comprobante_urls', 
                            name: 'comprobante_urls',
                            render: function(data) {
                                let html = "";
                                  data.forEach(url => {
                                                    let imagen = url.replace(/^.*[\\\/]/, ''); // Extrae el nombre del archivo
                                                    let carpeta = url.includes("comprobantes") ? "comprobantes" : "ordenes"; // Detecta la carpeta
                                                    html += `<a href="#" class="mover-imagen" data-imagen="${imagen}" data-carpeta="${carpeta}">
                                                        <i class="fa fa-camera"></i>
                                                     </a>`;
                                                             
                                                });
                                return html;
                                
                            }
                        },
                        { 
                            data: 'orden_urls', 
                            name: 'orden_urls',
                            render: function(data) {
                                let html = "";
                                 data.forEach(url => {
                                                    let imagen = url.replace(/^.*[\\\/]/, ''); // Extrae el nombre del archivo
                                                    let carpeta = url.includes("comprobantes") ? "comprobantes" : "ordenes"; // Detecta la carpeta
                                                    html += `<a href="#" class="mover-imagen" data-imagen="${imagen}" data-carpeta="${carpeta}">
                                                        <i class="fa fa-camera"></i>
                                                     </a>`;
                                                });
                                return html;
                            }
                        },
                        {
                        data: 'pdf_urls',
                        name: 'pdf_urls',
                        render: function(data) {
                            let html = "";
                            if (Array.isArray(data)) {
                                data.forEach(url => {
                                    let imagen = url.replace(/^.*[\\\/]/, ''); // Extrae el nombre del archivo
                                    let carpeta = url.includes("comprobantes") ? "comprobantes" : "escaner/temp"; // Detecta la carpeta
                                    html += `<a href="#" class="mover-imagen" data-imagen="${imagen}" data-carpeta="${carpeta}">
                                        <i class="fa fa-file"></i>
                                     </a>`;
                                });
                            }
                            return html;
                        }
                    },
                        { data: 'usuario', name: 'usuario' },
                        { data: 'created_at', name: 'created_at' }
                    ],
                    columnDefs: [
                            { targets: [1], visible: false, searchable: false }
                        ],


             //Botones----------------------------------------------------------------------

             "dom":'<"row"<"col-xs-1 form-inline"><"col-md-4 form-inline"l><"col-md-5 form-inline"f><"col-md-3 form-inline"B>>rt<"row"<"col-md-8 form-inline"i> <"col-md-4 form-inline"p>>',

             buttons: [
                          {

                       extend:'copyHtml5',
                       titleAttr: 'Copiar Registros',
                       title:"Scann api",
                       className: "btn  btn-outline-primary btn-sm"


                          },
                          {

                       extend:'excelHtml5',
                       titleAttr: 'Exportar Excel',
                       title:"Scann api",
                       className: "btn  btn-outline-success btn-sm",
                       exportOptions: {
                                            columns: [1, 2, 6, 7]
                                        },


                          },
                           {

                       extend:'csvHtml5',
                       titleAttr: 'Exportar csv',
                       className: "btn  btn-outline-warning btn-sm"

                          },
                          {

                       extend:'pdfHtml5',
                       titleAttr: 'Exportar pdf',
                       className: "btn  btn-outline-secondary btn-sm"


                          }
                       ],



 }); 




    
    
    //Función para abrir modal y prevenir el cierre de creación de listas
    $(document).on('click', '.create_scann', function () {
        
        console.log('ingresa a modal');
            // Resetear el formulario sin eliminar su estructura
        $('#modal_scann form')[0].reset(); 
    
        // Limpiar imágenes previas y video, pero mantener el formulario intacto
        $("#preview_comprobante, #preview_orden").empty(); // Vacía los contenedores de imágenes
        $("#videoStream").remove(); // Elimina el video si existe
    
        // Reabrir el modal sin eliminar su contenido original
        $('#modal_scann').modal({ backdrop: 'static', keyboard: false });
         $('#modal_scann').modal('show');

    });
    
                          // Evento para el botón del comprobante
                    $(document).on("click", "#comprobante", function () {
                        abrirCamara("comprobante");
                        
                    });
                    
                    // Evento para el botón de la orden
                    $(document).on("click", "#orden", function () {
                        abrirCamara("orden");
                    });
                    
                     $(document).on("click", "#btnEnviarFotos", function () {
                        enviarFotos();
                    });

    
                 function abrirCamara(tipo) {
             
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                            alert("Tu navegador no soporta acceso a la cámara");
                            return;
                        }
                    
                        let modalBody = document.querySelector("#modal_scann .card-body");
                    
                        // Crear el contenedor del video si no existe
                        let videoContainer = document.getElementById("videoContainer");
                        if (!videoContainer) {
                            videoContainer = document.createElement("div");
                            videoContainer.id = "videoContainer";
                            videoContainer.style.position = "relative"; 
                            videoContainer.style.width = "100%";
                            videoContainer.style.height = "300px"; // Tamaño fijo para evitar fullscreen
                            videoContainer.style.borderRadius = "5px";
                            videoContainer.style.overflow = "hidden"; 
                            videoContainer.style.background = "black";
                            modalBody.appendChild(videoContainer);
                        }
                    
                        // Crear el video si no existe
                        let video = document.getElementById("videoStream");
                        if (!video) {
                            video = document.createElement("video");
                            video.id = "videoStream";
                            video.autoplay = true;
                            video.playsInline = true; // Clave para evitar pantalla completa en móviles
                            video.style.width = "100%";
                            video.style.height = "100%";
                            video.style.objectFit = "contain"; // Ajustar sin salir del contenedor
                            videoContainer.appendChild(video);
                        }
                    
                    
                     
                        // Crear el botón flotante dentro del contenedor del video
                        let btnTomarFoto = document.getElementById("btnTomarFoto");
                        if (!btnTomarFoto) {
                            btnTomarFoto = document.createElement("button");
                            btnTomarFoto.id = "btnTomarFoto";
                            btnTomarFoto.className = "btn btn-primary";
                            btnTomarFoto.innerHTML = "📸"; 
                            btnTomarFoto.style.position = "absolute";
                            btnTomarFoto.style.bottom = "10px";
                            btnTomarFoto.style.left = "50%";
                            btnTomarFoto.style.transform = "translateX(-50%)";
                            btnTomarFoto.style.borderRadius = "50%";
                            btnTomarFoto.style.width = "60px";
                            btnTomarFoto.style.height = "60px";
                            btnTomarFoto.style.fontSize = "24px";
                            btnTomarFoto.style.zIndex = "10"; 
                            videoContainer.appendChild(btnTomarFoto);
                        }
                    
                        // Evento de captura
                        btnTomarFoto.onclick = () => capturarFoto(video, tipo);
                    
                        // Solicitar acceso a la cámara con restricciones
                        navigator.mediaDevices.getUserMedia({ 
                            video: { width: { ideal: 1024 }, height: { ideal: 576 }, facingMode: "environment" } 
                        })
                        .then(stream => {
                            video.srcObject = stream;
                        })
                        .catch(error => {
                            console.error("Error al abrir la cámara:", error);
                            alert("No se pudo acceder a la cámara. Verifica los permisos.");
                        });
                    
                        // Mostrar el modal
                        $('#modal_scann').modal('show');
                    }

  function capturarFoto(video, tipo) {
      
                let canvas = document.createElement("canvas");
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
            
                // Definir el nuevo alto para recortar la parte inferior
                let croppedHeight = Math.floor(canvas.height * 0.95); // Mantén el 50% superior
                canvas.height = croppedHeight; // Ajustar la altura del canvas
            
                let ctx = canvas.getContext("2d");
                
                ctx.imageSmoothingEnabled = true;
                ctx.imageSmoothingQuality = "high";
            
                // 📌 Capturar solo la parte superior de la imagen
                ctx.drawImage(video, 0, 0, canvas.width, croppedHeight, 0, 0, canvas.width, croppedHeight);
            
                // 📌 Convertir la imagen a escala de grises
                let imageData = ctx.getImageData(0, 0, canvas.width, croppedHeight);
                let pixels = imageData.data;
            
                for (let i = 0; i < pixels.length; i += 4) {
                    let avg = (pixels[i] + pixels[i + 1] + pixels[i + 2]) / 3; // Promedio RGB
                    pixels[i] = avg; // Rojo
                    pixels[i + 1] = avg; // Verde
                    pixels[i + 2] = avg; // Azul
                }
            
                ctx.putImageData(imageData, 0, 0);
            
                // 📌 Convertir a base64 en formato JPEG
                let fotoData = canvas.toDataURL("image/jpeg", 1);
                
                
                // 📌 Guardar imágenes según el tipo 
                if (tipo === "comprobante")
                
                { fotosComprobante.push(fotoData);
                
                //if (fotosComprobante.length === 1) 
                
            //    { extraerCodigo(fotoData);
              //  }
                
                } else if (tipo === "orden") 
                
                { fotosOrden.push(fotoData);
                }
            
                // 📌 Crear la vista previa de la imagen capturada
                let imgElement = document.createElement("img");
                imgElement.src = fotoData;
                imgElement.style.width = "90px";
                imgElement.style.height = "90px";
                imgElement.style.margin = "5px";
                imgElement.style.borderRadius = "5px";
                imgElement.style.cursor = "pointer";
            
                imgElement.addEventListener("click", function () {
                    mostrarImagenGrande(fotoData, tipo, imgElement);
                });
            
                let previewContainer = tipo === "comprobante"
                    ? document.getElementById("preview_comprobante")
                    : document.getElementById("preview_orden");
            
                previewContainer.appendChild(imgElement);
            
                // 📌 Agregar el botón de enviar fotos si no existe
                let btnEnviarFotos = document.getElementById("btnEnviarFotos");
                if (!btnEnviarFotos) {
                    btnEnviarFotos = document.createElement("button");
                    btnEnviarFotos.id = "btnEnviarFotos";
                    btnEnviarFotos.className = "btn btn-success";
                    btnEnviarFotos.textContent = "📤"; // Icono de envío
                    btnEnviarFotos.style.position = "absolute";
                    btnEnviarFotos.style.bottom = "10px";
                    btnEnviarFotos.style.right = "10px"; // Alineado a la derecha
                    btnEnviarFotos.style.borderRadius = "50%";
                    btnEnviarFotos.style.width = "60px";
                    btnEnviarFotos.style.height = "60px";
                    btnEnviarFotos.style.fontSize = "24px";
                    btnEnviarFotos.style.zIndex = "10";
                    btnEnviarFotos.style.display = "none"; // Oculto hasta que haya fotos
                    
                    videoContainer.appendChild(btnEnviarFotos);
                }
            
                if (fotosComprobante.length > 0 || fotosOrden.length > 0) {
                    btnEnviarFotos.style.display = "block";
                }
            }
            


                function mostrarImagenGrande(fotoData, tipo, imgElement) {
                        let modal = document.getElementById("modal_imagen");
                        let modalImg = document.getElementById("imagen_ampliada");
                        let btnEliminar = document.getElementById("btnEliminarFoto");
                    
                        if (!modal) {
                            // Crear modal si no existe
                            modal = document.createElement("div");
                            modal.id = "modal_imagen";
                            modal.style.position = "fixed";
                            modal.style.top = "0";
                            modal.style.left = "0";
                            modal.style.width = "100vw";
                            modal.style.height = "100vh";
                            modal.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
                            modal.style.display = "flex";
                            modal.style.alignItems = "center";
                            modal.style.justifyContent = "center";
                            modal.style.zIndex = "1050";
                            modal.style.cursor = "pointer";
                            modal.style.flexDirection = "column";
                    
                            // Crear la imagen
                            modalImg = document.createElement("img");
                            modalImg.id = "imagen_ampliada";
                            modalImg.style.maxWidth = "90%";
                            modalImg.style.maxHeight = "80%";
                            modalImg.style.borderRadius = "10px";
                            modalImg.style.boxShadow = "0px 0px 10px rgba(255, 255, 255, 0.5)";
                            modalImg.style.transition = "transform 0.3s ease-in-out";
                    
                            // Crear botón de eliminar
                            btnEliminar = document.createElement("button");
                            btnEliminar.id = "btnEliminarFoto";
                            btnEliminar.textContent = "Eliminar Foto";
                            btnEliminar.style.marginTop = "15px";
                            btnEliminar.style.padding = "10px 20px";
                            btnEliminar.style.backgroundColor = "red";
                            btnEliminar.style.color = "white";
                            btnEliminar.style.border = "none";
                            btnEliminar.style.borderRadius = "5px";
                            btnEliminar.style.cursor = "pointer";
                            btnEliminar.style.fontSize = "16px";
                    
                            modal.appendChild(modalImg);
                            modal.appendChild(btnEliminar);
                            document.body.appendChild(modal);
                        }
                    
                        modalImg.src = fotoData;
                        modal.style.display = "flex";
                    
                        // Evento para eliminar la foto
                        btnEliminar.onclick = function () {
                            eliminarFoto(fotoData, tipo, imgElement, modal);
                        };
                    
                        // Cerrar modal al hacer clic fuera de la imagen
                        modal.addEventListener("click", function (event) {
                            if (event.target === modal) {
                                modal.style.display = "none";
                            }
                        });
                    }
                    
                    function eliminarFoto(fotoData, tipo, imgElement, modal) {
                        // Eliminar la imagen del array
                        
                       
                        if (tipo == "comprobante") {
                            
                            let index = fotosComprobante.indexOf(fotoData);
                            if (index !== -1) {
                                fotosComprobante.splice(index, 1);
                                console.log("Foto eliminada correctamente.");
                            } else {
                                console.log("No se encontró la foto en fotosComprobante.");
                            }

                            
                            
                         
                        } else if (tipo == "orden") {
                            
                                let index = fotosOrden.indexOf(fotoData);
                            if (index !== -1) {
                                fotosOrden.splice(index, 1);
                                console.log("Foto eliminada correctamente.");
                            } else {
                                console.log("No se encontró la foto en fotosOrden.");
                            }
                            
                           
                            
                        }
                    
                        // Verificar en consola
                        console.log("Fotos comprobante:", fotosComprobante);
                        console.log("Fotos orden:", fotosOrden);
                    
                        // Eliminar la imagen de la vista previa
                        if (imgElement && imgElement.parentNode) {
                            imgElement.parentNode.removeChild(imgElement);
                        }
                    
                        // Cerrar el modal
                        modal.style.display = "none";
                    
                        // Ocultar botón de enviar fotos si ya no hay imágenes
                        let btnEnviarFotos = document.getElementById("btnEnviarFotos");
                        if (btnEnviarFotos && fotosComprobante.length === 0 && fotosOrden.length === 0) {
                            btnEnviarFotos.style.display = "none";
                        }
                    }
                                    
                function extraerCodigo(foto) {
                    Tesseract.recognize(
                        foto, 'eng',
                        { logger: m => console.log(m) }
                    ).then(({ data: { text } }) => {
                        let codigo = text.trim();
                        let inputCodigo = document.getElementById("codigo");
                
                        if (codigo.length > 0 && /^[A-Za-z0-9]+$/.test(codigo)) {
                            inputCodigo.value = codigo; // Si el código parece válido, lo coloca en el input
                        } else {
                            inputCodigo.value = ""; // Deja el campo vacío para que el usuario escriba
                            inputCodigo.placeholder = "Ingrese el código manualmente"; // Indicación visual
                        }
                
                        // Hacer visible el campo para que el usuario pueda corregirlo si es necesario
                        inputCodigo.style.display = "block";
                    }).catch(err => {
                        console.error("Error al extraer código:", err);
                        let inputCodigo = document.getElementById("codigo");
                        inputCodigo.value = "";
                        inputCodigo.placeholder = "Ingrese el código manualmente";
                        inputCodigo.style.display = "block"; // Mostrar el campo si la extracción falla
                    });
                }
                                
               function mostrarPreview(foto, idPreview) {
                        let contenedor = document.getElementById("previewContainer");
                        let img = document.createElement("img");
                        img.src = foto;
                        img.width = 80;
                        img.style.margin = "5px";
                        contenedor.appendChild(img);
                    }
                    
             async function enviarFotos() {
                 
                            var usuarioLogueado = @json(auth()->user()->name ?? 'usuario_demo');
                            let codigo = document.getElementById("codigo").value.trim(); // Elimina espacios en blanco
                        
                            // Validar que el código no esté vacío
                            if (!codigo) {
                                Swal.fire({
                                    icon: "warning",
                                    title: "Código requerido",
                                    text: "Debes ingresar un código antes de continuar.",
                                });
                                return;
                            }
                        
                            // Validar formato del código (solo letras y números)
                            if (!/^[A-Za-z0-9]+$/.test(codigo)) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Código inválido",
                                    text: "El código ingresado debe contener solo letras y números.",
                                });
                                return;
                            }
                        
                            // Verificar que al menos una foto haya sido tomada
                            if (fotosComprobante.length === 0 && fotosOrden.length === 0) {
                                Swal.fire({
                                    icon: "warning",
                                    title: "Fotos requeridas",
                                    text: "Debes tomar al menos una foto antes de continuar.",
                                });
                                return;
                            }
                        
                            try {
                                // Mostrar mensaje de carga
                                Swal.fire({
                                    icon: "info",
                                    title: 'Subiendo fotos...',
                                    html: 'Por favor, espere un momento.',
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    willOpen: () => {
                                        Swal.showLoading();
                                    },
                                });
                        
                                let response = await fetch("{{ route('uploadScann') }}", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "Accept": "application/json",  // 
                                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                    },
                                    body: JSON.stringify({
                                        codigo: codigo,
                                        comprobante: fotosComprobante,
                                        orden: fotosOrden,
                                        usuario: usuarioLogueado
                                    })
                                });
                        
                                let data = await response.json();
                        
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Fotos cargadas correctamente',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                        
                                    // Limpiar datos después del envío exitoso
                                    fotosComprobante = [];
                                    fotosOrden = [];
                        
                                    // Ocultar modal (si usas Bootstrap y jQuery)
                                    $("#modal_scann").modal('hide');
                        
                                    // Limpiar campos
                                    document.getElementById("preview_comprobante").innerHTML = "";
                                    document.getElementById("preview_orden").innerHTML = "";
                                    document.getElementById("codigo").value = "";
                        
                                    // Recargar DataTable
                                    $('#scann_api').DataTable().ajax.reload(null, false);
                                } else {
                                                                       // Si hay errores de validación
                                        if (data.errors) {
                                            let errorMessages = Object.values(data.errors).flat().join("\n");
                                            
                                            Swal.fire({
                                                icon: "error",
                                                title: "Errores de validación",
                                                text: errorMessages,
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: "error",
                                                title: "Error al enviar las fotos",
                                                text: data.message || "Hubo un problema desconocido. Inténtalo nuevamente.",
                                            });
                                        }
                                }
                            } catch (error) {
                                
                                console.error("Error en la petición:", error);
                                Swal.fire({
                                    icon: "error",
                                    title: "Error de conexión",
                                    text: "No se pudo conectar con el servidor. Verifica tu conexión a internet e inténtalo nuevamente.",
                                });
                            }
                        }
                        
                        
                        
                            $(document).on('click', '.detalle-orden', function () {
                                let ordenId = $(this).data('id');
                                window.location.href = `detalleScann/${ordenId}`;
                            });
              
                        
                        
$(document).on('click', '.eliminar-orden', function() {
                    let ordenId = $(this).data('id');
                
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `ordenes/${ordenId}`,
                                type: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                success: function(response) {
                                    Swal.fire('Eliminado', response.message, 'success');
                                    $('#scann_api').DataTable().ajax.reload(null, false);
                                },
                                error: function(xhr) {
                                    Swal.fire('Error', 'No se pudo eliminar la orden.', 'error');
                                }
                            });
                        }
                    });
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
