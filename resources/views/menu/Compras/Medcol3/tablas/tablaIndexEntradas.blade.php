<div class="row">
    <div class="col-lg-12">      
        <div class="card card-success">
            <div class="card-header with-border">
                <h3 class="card-title-1">Lista de Ordenes</h3>                  
            </div>
            <div class="card-body table-responsive p-2 ">

                <!-- Formulario de búsqueda fuera de la tabla -->
                <form id="buscarOrdenesForm" method="GET" action="{{ route('buscar.ordenes.compra') }}" class="mb-4 p-3 rounded shadow-sm bg-light">
                    <div class="form-row">
                        <!-- Orden -->
                        <div class="form-group col-md-2">
                            <label for="orden_de_compra" class="font-weight-bold mb-1">
                                <i class="fas fa-file-invoice mr-1"></i>Orden
                            </label>
                            <input type="text" class="form-control form-control-sm" name="orden_de_compra" value="{{ request('orden_de_compra') }}" placeholder="Ej: 15-05-2025 Orden">
                        </div>
                
                        <!-- Fecha Desde -->
                        <div class="form-group col-md-2">
                            <label for="fecha_desde" class="font-weight-bold mb-1">
                                <i class="fas fa-calendar-alt mr-1"></i>Desde
                            </label>
                            <input type="date" class="form-control form-control-sm" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}">
                        </div>
                
                        <!-- Fecha Hasta -->
                        <div class="form-group col-md-2">
                            <label for="fecha_hasta" class="font-weight-bold mb-1">
                                <i class="fas fa-calendar-alt mr-1"></i>Hasta
                            </label>
                            <input type="date" class="form-control form-control-sm" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}">
                        </div>
                
                        <!-- Centro de Producción -->
                        <div class="form-group col-md-3">
                            <label for="cod_farmacia" class="font-weight-bold mb-1">
                                <i class="fas fa-clinic-medical mr-1"></i>Centro de Producción
                            </label>
                            <select name="cod_farmacia" id="cod_farmacia" class="form-control form-control-sm select2bs4">
                                <option value="">Seleccione una farmacia...</option>
                                @php
                                    $farmacias = [
                                        'BIO1' => 'FARMACIA BIOLÓGICOS',
                                        'DLR1' => 'FARMACIA DOLOR',
                                        'DPA1' => 'FARMACIA PALIATIVOS',
                                        'EHU1' => 'FARMACIA HUÉRFANAS',
                                        'EM01' => 'FARMACIA EMCALI',
                                        'EVEN' => 'FARMACIA EVENTO',
                                        'EVSM' => 'EVENTO SALUD MENTAL',
                                        'FRJA' => 'FARMACIA JAMUNDÍ',
                                        'INY'  => 'FARMACIA INYECTABLES',
                                        'PAC'  => 'FARMACIA PAC',
                                        'SM01' => 'FARMACIA SALUD MENTAL',
                                        'BPDT' => 'BOLSA',
                                        'FRIO' => 'FARMACIA IDEO',
                                        'EVIO' => 'EVENTO IDEO',
                                        'BDNT' => 'BOLSA NORTE',
                                        'COOS' => 'FARMACIA COOSALUD',
                                        'COMF' => 'FARMACIA COMFENALCO',
                                        'FPEND' => "FARMACIA PENDIENTES"
                                    ];
                                @endphp
                                @foreach($farmacias as $codigo => $nombre)
                                    <option value="{{ $codigo }}" {{ request('cod_farmacia') == $codigo ? 'selected' : '' }}>
                                        {{ $codigo }} - {{ $nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                
                        <!-- Proveedor -->
                        <div class="form-group col-md-3">
                            <label for="proveedor" class="font-weight-bold mb-1">
                                <i class="fas fa-truck mr-1"></i>Proveedor
                            </label>
                            <input type="text" class="form-control form-control-sm" name="proveedor" value="{{ request('proveedor') }}" placeholder="Ej: Proveedor XYZ">
                        </div>
                
                        <!-- Estado -->
                        <div class="form-group col-md-3">
                            <label for="estado" class="font-weight-bold mb-1">
                                <i class="fas fa-clipboard-check mr-1"></i>Estado
                            </label>
                            <select name="estado" id="estado" class="form-control form-control-sm select2bs4" style="height: 40px; width: 100%;">
                                <option value="">Seleccione un estado</option>
                                <option value="COMPLETADA" {{ request('estado') == 'COMPLETADA' ? 'selected' : '' }}>COMPLETADA</option>
                                <option value="PENDIENTE" {{ request('estado') == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                                <option value="ANULADA" {{ request('estado') == 'ANULADA' ? 'selected' : '' }}>ANULADA</option>
                            </select>
                        </div>
                
                        <!-- Usuario -->
                        <div class="form-group col-md-3">
                            <label for="userCreate" class="font-weight-bold mb-1 d-block">
                                <i class="fas fa-user mr-1"></i>Usuario
                            </label>
                            <select name="userCreate" id="userCreate" class="form-control select2bs4" style="height: 30px; width: 100%;">
                                <!-- Opciones cargadas por AJAX -->
                            </select>
                        </div>

                        <!--Clasificación de la orden -->
                        <div class="form-group col-md-3">
                            <label for="ClasiOrden" class="font-weight-bold mb-1">
                                <i class="fas fa-clipboard-check mr-1"></i>Clasificación de la orden
                            </label>
                            <select name="clasi_orden" id="ClasiOrden" class="form-control form-control-sm select2bs4">
                                <option value="">Seleccione un estado</option>
                                <option value="PG"  {{ request('clasi_orden') == 'PG' ? 'selected' : '' }}>Pedido General</option>
                                <option value="Queja" {{ request('clasi_orden') == 'Queja' ? 'selected' : '' }}>Queja</option>
                                <option value="Pendiente" {{ request('clasi_orden') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Contingencia" {{ request('clasi_orden') == 'Contingencia' ? 'selected' : '' }}>Contingencia</option>
                            </select>
                        </div>
                
                        <!-- Botón -->
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="submit" id="buscarOrdenesBtn" class="btn btn-success btn-block">
                                <i class="fas fa-search mr-1"></i>Buscar
                            </button>
                            <button type="button" id="ExportarReporte" class="btn btn-info btn-block" style="margin-left: 10px;color: white;">
                                 <i class="fas fa-file mr-1" style="width: 110px;"></i>Expor. Ordenes
                            </button>
                            <button type="button" id="ExportarReporteDetalle" class="btn btn-info btn-block" style="margin-left: 10px;color: white;">
                                 <i class="fas fa-file mr-1" style="width: 110px;"></i>Expor. Detalles
                            </button>
                        </div>
                    </div>
                </form>




   <table id="ordenes" class="table table-hover text-nowrap">
    <thead>
        @include('menu.usuario.form.forminformecompras')
        <tr>
            <th>#Orden Compra</th>
            <th>Farmacia</th>
            <th>Fecha Pedido</th>
            <th>Proveedor</th>
            <th>Código Proveedor</th>
            <th>Teléfono</th>
            <th>Total</th> 
            <th>Estado</th>                             
            <th>Acciones</th>
        </tr>
    </thead>

    
    <tbody id="ordenes-body">
        @include('menu.Compras.Medcol3.tablas.tablaIndexOrdenes')
    </tbody>
</table>

<div id="paginacion">
    {{ $ordenes->links() }}
</div>



            </div>
        </div>
    </div>
</div>

<script>
    
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
