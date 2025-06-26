$(document).ready(function () {
    
    
       // Funcion para pintar con data table
var datatable = $('#scann_api').DataTable({
            language: idioma_espanol,
            processing: true,
            lengthMenu: [ [25, 50, 100, 500, -1 ], [25, 50, 100, 500, "Mostrar Todo"] ],
            processing: true,
            serverSide: true,
            aaSorting: [[ 0, "asc" ]],
            ajax:{
              url:"{{route('indexscann')}}",
                 },
            columns: [
                
                  {data:'id'},
                  {data:'codigo'},
                  {data:'comprobante'},
                  {data:'orden'},
                  {data:'usuario'},
                  {data:'created_at'},
                
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
                       className: "btn  btn-outline-success btn-sm"


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

        $('#modal_scann').modal({ backdrop: 'static', keyboard: false });
        $('#modal_scann').modal('show');


    });
    

let fotosComprobante = [];
let fotosOrden = [];
    
    function abrirCamara(tipo) {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert("Tu navegador no soporta acceso a la cámara");
            return;
        }
    
        let video = document.createElement("video");
        video.style.display = "none";
        document.body.appendChild(video);
    
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                video.play();
                setTimeout(() => capturarFoto(video, stream, tipo), 2000);
            })
            .catch(error => console.error("Error al abrir la cámara:", error));
    }
    
    function capturarFoto(video, stream, tipo) {
        let canvas = document.createElement("canvas");
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        let ctx = canvas.getContext("2d");
    
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        let foto = canvas.toDataURL("image/jpeg");
    
        stream.getTracks().forEach(track => track.stop());
        video.remove();
    
        if (tipo === 'comprobante' && fotosComprobante.length < 10) {
            fotosComprobante.push(foto);
            mostrarPreview(foto, "preview_comprobante");
            
            if (fotosComprobante.length === 1) {
                extraerCodigo(foto);
            }
        } else if (tipo === 'orden' && fotosOrden.length < 10) {
            fotosOrden.push(foto);
            mostrarPreview(foto, "preview_orden");
        } else {
            alert("Solo puedes tomar hasta 10 fotos por categoría.");
        }
    }
    
    function extraerCodigo(foto) {
        Tesseract.recognize(
            foto, 'eng',
            { logger: m => console.log(m) }
        ).then(({ data: { text } }) => {
            let codigo = text.trim();
            document.getElementById("codigo").value = codigo;
        });
    }
    
    function mostrarPreview(foto, idPreview) {
        let contenedor = document.getElementById(idPreview);
        let img = document.createElement("img");
        img.src = foto;
        img.width = 80;
        img.style.margin = "5px";
        contenedor.appendChild(img);
    }
    
    function enviarFotos() {
        let codigo = document.getElementById("codigo").value;
    
        if (!codigo) {
            alert("Debes ingresar un código.");
            return;
        }
    
        if (fotosComprobante.length === 0 && fotosOrden.length === 0) {
            alert("Debes tomar al menos una foto.");
            return;
        }
    
        fetch("{{ route('uploadScann') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                codigo: codigo,
                comprobante: fotosComprobante,
                orden: fotosOrden,
                usuario: "usuario_demo"
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Fotos enviadas correctamente.");
                fotosComprobante = [];
                fotosOrden = [];
                document.getElementById("preview_comprobante").innerHTML = "";
                document.getElementById("preview_orden").innerHTML = "";
            } else {
                alert("Error al enviar las fotos.");
            }
        })
        .catch(error => console.error("Error en la petición:", error));
    }
    
    
    
    //------------------------------------------------------Funciones de Listas-----------------------------------------//

    
});
    