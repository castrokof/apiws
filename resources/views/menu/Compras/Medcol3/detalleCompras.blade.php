@extends("theme.$theme.layout") 
@section('titulo')
    Ordenes de Compras
@endsection
@section('contenido')
@section('styles')
<link href="{{ asset("assets/$theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css") }}" rel="stylesheet"
        type="text/css" />
    <link href="{{asset("assets/css/select2-bootstrap.min.css")}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

@endsection

@if(session('error'))
   <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>error!</strong> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
@endif
@if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>¡Éxito!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')
        
        <span id="form_result"></span>
        <div id="card-drawel" class="card card-info">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Administrar Odenes de Compra - Detalles</h3>
                
                <div class="ml-auto d-flex">                   
                        <a href="{{ route('ordenes.exportar.pdf', $ordenes['infoOrden']->num_orden_compra) }}" 
                        class="btn btn-success mx-1" target="_blank">
                            <i class="fa fa-file-pdf"></i> Ver PDF
                        </a>      
                       @if(Auth::user()->rol != '6')
                        <a href="{{ route('ordenes.editar', $ordenes['infoOrden']->id) }}" class="btn btn-warning mx-1">
                            <i class="fas fa-pencil-alt"></i> Editar Orden
                        </a>     
                        @endif        
                        <a href="{{ route('compras.medcol3') }}" class="btn btn-danger mx-1">
                            <i class="fa fa-arrow-left"></i> Atrás
                        </a>
                    <div class="ml-auto d-flex">
                    
                </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<div class="container" style="max-width: 1700px;">
    <h3>Detalle de Orden de Compra #{{ $ordenes['infoOrden']->orden_de_compra }}</h3>

    <div style="display: flex; justify-content: space-between; gap: 1rem;">

        
        <div class="card-body">
            <p><strong>Proveedor:</strong> {{ $ordenes['infoOrden']->proveedor }}</p>
            <p><strong>NIT:</strong> {{ $ordenes['infoOrden']->nit }}</p>
            <p><strong>Teléfono:</strong> {{ $ordenes['infoOrden']->telefono }}</p>
            <p><strong>Dirección:</strong> {{ $ordenes['infoOrden']->direccion }}</p>
            <p><strong>Codigo Farmacia:</strong> {{ $ordenes['infoOrden']->cod_farmacia }}</p>
            <p id="totalParcial-orden"><strong>Total Parcial:</strong> {{ number_format($ordenes['infoOrden']->totalParcial, 2) }}</p>
            @if($ordenes['infoOrden']->estado == "Pendiente")
             <p class="estado-orden" style="background-color: red; color: white;"><strong>Estado:</strong> {{ $ordenes['infoOrden']->estado }}</p>       
            @endif
            @if($ordenes['infoOrden']->estado == "Completada")
             <p class="estado-orden" style="background-color: green; color: white;"><strong>Estado:</strong> {{ $ordenes['infoOrden']->estado }}</p>       
            @endif
            @if($ordenes['infoOrden']->estado == "Anulada")
             <p class="estado-orden" style="background-color: red; color: white;"><strong>Estado:</strong> {{ $ordenes['infoOrden']->estado }}</p>       
            @endif
            

        </div>
        <div class="card-body"> 
            <p><strong>Observaciones:</strong> {{ $ordenes['infoOrden']->observaciones }}</p>
            <p><strong>Email:</strong> {{ $ordenes['infoOrden']->email }}</p>
            <p><strong>Fecha:</strong> {{ $ordenes['infoOrden']->fecha }}</p>
            <p><strong>Realizado Por:</strong> {{ $ordenes['Usuario']->name}}</p> 
            <p><strong>Facturas Asociadas:</strong></p>
            @foreach(explode(',', $ordenes['infoOrden']->facturas) as $factura)
                <span class="badge bg-primary me-1">{{ trim($factura) }}</span>
            @endforeach

            <p id="total-orden"><strong>Total Orden:</strong> {{ number_format($ordenes['infoOrden']->total, 2) }}</p>


            
            
            
            

        </div>
    </div>

    <div class="card">
        <div class="card-header">Detalles de la Orden
            <a href="#"
            class="btn btn-success btn-sm rounded-pill btn-agregar-factura"
            style="font-weight: 500;"
            data-bs-toggle="modal"
            data-bs-target="#modalFacturas"
            data-orden-id="{{ $ordenes['infoOrden']->id }}"
            data-comentario="{{ e($ordenes['infoOrden']->facturas ?? '') }}">
                <i class="fa fa-plus"></i> Facturas
            </a>


             @if(Auth::user()->rol != '6')
            @if($ordenes['infoOrden']->estado != "Completada")
                <a href="#"
                class="btn btn-success btn-sm rounded-pill"
                style="font-weight: 500;"
                data-bs-toggle="modal"
                data-bs-target="#modalArticulo"
                data-orden-id="{{ $ordenes['infoOrden']->num_orden_compra }}">
                    <i class="fa fa-plus"></i> Molecula
                </a>
            
            @endif
            @endif

            
        </div>
        <div class="card-body table-responsive">
        <div id="tabla-moleculas-wrapper">
            @include('menu.Compras.Medcol3.tablas.tablaIndexMoleculas')
        </div>
        </div>
    </div>
</div>

@endsection
<!-- Modal Comentario-->
<div class="modal fade" id="comentarioModal" tabindex="-1" aria-labelledby="comentarioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
                
        <form method="POST" action="{{ route('guardar.comentario') }}">
            @csrf
            <input type="hidden" name="detalle_id" id="modal_detalle_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="comentarioModalLabel">Agregar Observación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="modal_comentario">Observaciones:</label>
                    <textarea name="observaciones" id="modal_comentario" class="form-control" rows="4"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- Modal Facturas-->
<div class="modal fade" id="modalFacturas" tabindex="-1" aria-labelledby="modalFacturasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('guardar.factura') }}">
            @csrf
            {{-- Campo oculto para enviar el ID de la orden --}}
            <input type="hidden" name="orden_id" id="modal_orden_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFacturasLabel">Agregar Número de Factura</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{-- Campo de texto para el número de factura --}}
                    <div class="mb-3">
                        <label for="numero_factura" class="form-label">Número de Factura:</label>
                        <input type="text" name="numero_factura" id="numero_factura" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Agregar Un articulo-->
<div class="modal fade" id="modalArticulo" tabindex="-1" aria-labelledby="modalArticuloLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form  method="POST" action="{{ route('guardar.articulo') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalArticuloLabel">Agregar Molecula</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          {{-- Formulario --}}
          <div class="form-group row">
              <div class="col-lg-4 position-relative" style="width: 1000px;"> <!-- Necesario para dropdown -->
             <label for="codigo" class="col-xs-4 control-label ">Código de Molecula</label>
            <select name="codigo" id="codigo" class="form-control select2bs4" style="width: 100%;" required>
            </select>
          </div>
              
          <div class="form-group row">
            
            <small for="molecula" class="text-muted">Código de Articulo</small>
            <input type="hidden" name="molecula" id="molecula" class="form-control" >
            <input type="hidden" name="orden_id" id="orden_id">
            </div>
            
            <div class="col-lg-2">
              <label for="marca" class="control-label">Marca</label>
              <input type="text" name="marca" id="marca" class="form-control" value="{{ old('marca') }}" readonly required>
            </div>
            <div class="col-lg-6">
              <label for="nombrea" class="control-label">Nombre artículo</label>
              <input type="text" name="nombrea" id="nombrea" class="form-control" value="{{ old('nombrea') }}" readonly required>
            </div>

            <div class="col-lg-6">
              <label for="cums" class="control-label">CUMS</label>
              <input type="text" name="cums" id="cums" class="form-control" value="{{ old('cums') }}" readonly required>
            </div>

            <div class="col-lg-3">
              <label for="presentacion" class="control-label">Presentación</label>
              <input type="text" name="presentacion" id="presentacion" class="form-control" value="{{ old('presentacion') }}" readonly required>
            </div>
          </div>

          <div class="form-group row mt-3">
            <div class="col-lg-2">
              <label for="cantidad" class="control-label">Cantidad</label>
              <input type="number" name="cantidad" id="cantidad" class="form-control" value="{{ old('cantidad') }}">
            </div>
            <div class="col-lg-3">
              <label for="precio_compra_subtotal_unitario" class="control-label">Valor Unitario</label>
              <input type="number" name="precio_compra_subtotal_unitario" id="precio_compra_subtotal_unitario" class="form-control" value="{{ old('precio_compra_subtotal_unitario') }}">
            </div>
            <div class="col-lg-2">
              <label for="ivab" class="control-label">% IVA</label>
              <select name="ivab" id="ivab" class="form-control">
                <option value="">Seleccione</option>
                <option value="0">0%</option>
                <option value="5">5%</option>
                <option value="19">19%</option>
              </select>
              <small class="text-muted">% de Iva</small>
            </div>
            <div class="col-lg-2">
              <label for="iva" class="control-label">Valor IVA Unitario</label>
              <input type="text" name="iva" id="iva" class="form-control" readonly>
              <small class="text-muted">Valor iva</small>
            </div>
          </div>

          <div class="form-group row mt-3">
            <div class="col-lg-3">
              <label for="cantidad_iva_total" class="control-label">IVA Total</label>
              <input type="text" name="cantidad_iva_total" id="cantidad_iva_total" class="form-control" value="{{ old('cantidad_iva_total') }}" readonly>
            </div>
            <div class="col-lg-4">
              <label for="precio_compra_subtotal" class="control-label">Sub-Total</label>
              <input type="text" name="precio_compra_subtotal" id="precio_compra_subtotal" class="form-control" placeholder="$0.00" readonly>
            </div>
            <div class="col-lg-4">
              <label for="precio_compra_total" class="control-label">Total</label>
              <input type="text" name="precio_compra_total" id="precio_compra_total" class="form-control" placeholder="$0.00" readonly>
            </div>
          </div>

          <div class="form-group row mt-3">
            <div class="col-lg-6">
              <label for="usuario_id" class="control-label">Usuario</label>
              <input name="usuario_id" id="usuario_id" class="form-control" value="{{ Auth::user()->name ?? '' }}" readonly>
            </div>
            <div class="col-lg-6">
              <label for="created_at" class="control-label">Fecha</label>
              <input name="created_at" id="created_at" class="form-control" value="{{ now() ?? '' }}" readonly>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Guardar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </form>
  </div>
</div>


@section('scriptsPlugins')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="{{ asset('assets/js/jquery-select2/select2.min.js') }}" type="text/javascript"></script>
<script>
    
    document.addEventListener("DOMContentLoaded", function () {

$(document).on('click', '.btn-comentario', function () {
            const rawData = $(this).data('id'); // "69 | dsfsdfsdf"
            const partes = rawData.split('|');

            const detalleId = partes[0]?.trim();       // "69"
            const comentario = partes[1]?.trim();      // "dsfsdfsdf"

        $('#modal_detalle_id').val(detalleId);
        $('#modal_comentario').val(comentario);
        });
        // Mostrar modo de edición
document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const row = this.closest('tr');
                row.querySelector('.view-mode').style.display = 'none';
                row.querySelector('.edit-mode').style.display = 'block';
                row.querySelector('.edit-actions').style.display = 'inline-flex';
                this.style.display = 'none';
            });
        });
$('.btn-eliminar').on('click', function (){ 
        let id = $(this).data('id');
        let row = $(this).closest('tr');

        if (confirm('¿Estás seguro de que deseas eliminar esta molécula?')) {
            $.ajax({
                url: '{{ url("/ordenesDetalle") }}/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        row.remove(); // Elimina la fila de la tabla
                        // Actualizar el total de la orden si es necesario
                        if (response.Orden && response.Orden.total !== undefined) {
                            let total = parseFloat(response.Orden.total);
                            let totalFormateado = total.toLocaleString('es-CO', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            let totalParcial = parseFloat(response.Orden.totalParcial);
                            let totalParcialFormateado = totalParcial.toLocaleString('es-CO', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                            $('#total-orden').html('<strong>Total:</strong> ' + totalFormateado);
                            $('#totalParcial-orden').html('<strong>Total Parcial:</strong> ' + totalParcialFormateado);
                        }

                        alert(response.message);
                    } else {
                        alert('No se pudo eliminar la molécula.');
                    }
                },
                error: function () {
                    alert('Ocurrió un error en el servidor.');
                }
            });
        }
    });

    

        // Cancelar edición
document.querySelectorAll('.cancel-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const row = this.closest('tr');
                row.querySelector('.edit-mode').style.display = 'none';
                row.querySelector('.view-mode').style.display = 'block';
                row.querySelector('.edit-actions').style.display = 'none';
                row.querySelector('.edit-btn').style.display = 'inline-block';
            });
        });
        

        // Guardar cambios con AJAX
document.querySelectorAll('.save-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const row = this.closest('tr');
            const detalleId = row.querySelector('.editable-cell').getAttribute('data-detalle-id');
            const cantidad = row.querySelector('.cantidad-entregada-input').value;

            fetch('{{ route("orden.actualizarDetalle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    detalle_id: detalleId,
                    cantidadEntregada: cantidad
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data);
                    row.querySelector('.view-mode').textContent = cantidad;
                    const cantidadTotal = parseInt(row.querySelector('.cantidad-cell').textContent);
                    const faltantes = cantidadTotal - parseInt(cantidad);
                    row.querySelector('.faltantes-cell').textContent = faltantes;
                    
                    $('#totalParcial-orden').html('<strong>Total Parcial:</strong> ' + data.UpdatetotalParcial.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

                    
                    row.querySelector('.subtotal_molecula').textContent = data.ValorParcial.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                    if (faltantes === 0) {
                        row.querySelector('.estado-cell').textContent = "Completa";
                        row.querySelector('.estado-cell').style.backgroundColor = "green";
                        row.querySelector('.edit-btn').style.display = 'none'; // Ocultar botón de editar si está completa
                        this.style.display = 'none';
                    }
                    else
                    {
                        row.querySelector('.estado-cell').textContent = "Pendiente";
                        row.querySelector('.estado-cell').style.backgroundColor = "red";
                    }
                    

                    // Restaurar UI
                    row.querySelector('.edit-mode').style.display = 'none';
                    row.querySelector('.view-mode').style.display = 'block';
                    row.querySelector('.edit-actions').style.display = 'none';
                    row.querySelector('.edit-btn').style.display = 'inline-block';

                    if (faltantes === 0) {
                        row.querySelector('.edit-btn').style.display = 'none'; // Ocultar botón de editar si está completa
                        this.style.display = 'none';
                    }

                    // 🔁 Verificar si todos los detalles están en estado "Completa"
                    if (data.EsCompleta) {
                        const ordenId = document.getElementById('tabla-detalles').getAttribute('data-orden-id');

                        fetch('{{ route("orden.actualizarEstado") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                orden_id: ordenId,
                                nuevo_estado: "Completada"
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                const estadoOrden = document.getElementsByClassName('estado-orden')[0];
                                estadoOrden.textContent = "Completada";
                                estadoOrden.style.backgroundColor = "green";
                                estadoOrden.style.color = "white";
                                alert("Orden actualizada a COMPLETA");
                            }
                        })
                        .catch(err => {
                            console.error("Error al actualizar orden:", err);
                        });
                    }

                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al actualizar');
            });
        });

document.querySelectorAll('.btn-agregar-factura').forEach(btn => {
        btn.addEventListener('click', function () {
            const ordenId = this.getAttribute('data-orden-id');
            document.getElementById('modal_orden_id').value = ordenId;
        });
    });
 });

 
    
$('#modalArticulo').on('shown.bs.modal', function (event) {
    let button = $(event.relatedTarget);
   let ordenId = button.data('orden-id');

  // Asignar el ID de orden al input hidden
  $('#orden_id').val(ordenId);
      if ($('#codigo').children().length === 0) { // evitar recarga si ya están cargados
        $.ajax({
          url: "{{ route('selectarticulo3add') }}",
          type: 'GET',
          dataType: 'json',
          success: function (data) {
            $('#codigo').append('<option value="">Seleccione un artículo</option>');
            $.each(data, function (key, value) {
              $('#codigo').append('<option value="' + value.codigo + '">' + value.codigo + ' => ' + value.nombre + ' => '+ value.marca +'</option>');
            });

            $('#codigo').select2({
              theme: 'bootstrap4',
              dropdownParent: $('#modalArticulo') // importante para que el dropdown se muestre bien dentro del modal
            });
          }
        });
      }
    });

     $('#codigo').on('change', function () {
  let codigoSeleccionado = $(this).val();
const url = "{{ route('articulos.obtener', ':codigo') }}".replace(':codigo', encodeURIComponent(codigoSeleccionado));
  if (codigoSeleccionado) {
    $.ajax({
      url: url,
      type: 'GET',
      dataType: 'json',
      success: function (data) {
          
        $('#marca').val(data.marca);
        $('#nombrea').val(data.nombre);
        $('#cums').val(data.cums);
        $('#presentacion').val(data.forma);
      },
      error: function () {
        $('#marca, #nombrea, #cums, #presentacion').val('');
        alert('No se pudo cargar la información del artículo.');
      }
    });
  } else {
    $('#marca, #nombrea, #cums, #presentacion',).val('');
  }
});



function calcularTotales() {
    let cantidad = parseFloat($('#cantidad').val()) || 0;
    let valorUnitario = parseFloat($('#precio_compra_subtotal_unitario').val()) || 0;
    let ivaPorcentaje = parseFloat($('#ivab').val()) || 0;

    // Subtotal sin IVA
    let subtotal = cantidad * valorUnitario;

    // IVA unitario
    let ivaUnitario = valorUnitario * (ivaPorcentaje / 100);

    // Total IVA
    let ivaTotal = ivaUnitario * cantidad;

    // Total con IVA
    let total = subtotal + ivaTotal;

    // Actualizar los campos
    $('#precio_compra_subtotal').val(subtotal.toFixed(2));
    $('#iva').val(ivaUnitario.toFixed(2));
    $('#cantidad_iva_total').val(ivaTotal.toFixed(2));
    $('#precio_compra_total').val(total.toFixed(2));
  }

  $(document).ready(function () {
    // Ejecutar cuando se cambia cantidad, valor unitario o % IVA
    $('#cantidad, #precio_compra_subtotal_unitario, #ivab').on('input change', calcularTotales);
  });






});



</script>

@endsection
