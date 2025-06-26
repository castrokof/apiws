<div class="row">
    <div class="col-lg-12">      
        <div class="card card-success">
            <div class="card-header with-border">
                <h3 class="card-title-1">Lista de Ordenes</h3>                  
            </div>
            <div class="card-body table-responsive p-2 ">

                <!-- Formulario de búsqueda fuera de la tabla -->
                <form id="buscarOrdenesForm" method="GET" action="{{ route('buscar.ordenes.compra') }}" class="mb-4 p-3 rounded shadow-sm bg-light">
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-2">
                            <label for="orden_de_compra" class="font-weight-bold mb-1">
                                <i class="fas fa-file-invoice mr-1"></i>Orden
                            </label>
                            <input type="text" class="form-control form-control-sm" name="orden_de_compra" value="{{ request('orden_de_compra') }}" placeholder="Ej: 15-05-2025 Orden">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="fecha_desde" class="font-weight-bold mb-1">
                                <i class="fas fa-calendar-alt mr-1"></i>Desde
                            </label>
                            <input type="date" class="form-control form-control-sm" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="fecha_hasta" class="font-weight-bold mb-1">
                                <i class="fas fa-calendar-alt mr-1"></i>Hasta
                            </label>
                            <input type="date" class="form-control form-control-sm" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="cod_farmacia" class="font-weight-bold mb-1">
                                <i class="fas fa-clinic-medical mr-1"></i>Centro de Producción
                            </label>
                            <select name="cod_farmacia" id="cod_farmacia" class="form-control form-control-sm select2bs4">
                                <option value="">Seleccione una farmacia...</option>
                                <option value="BIO1" {{ request('cod_farmacia') == 'BIO1' ? 'selected' : '' }}>BIO1 - FARMACIA BIOLÓGICOS</option>
                                <option value="DLR1" {{ request('cod_farmacia') == 'DLR1' ? 'selected' : '' }}>DLR1 - FARMACIA DOLOR</option>
                                <option value="DPA1" {{ request('cod_farmacia') == 'DPA1' ? 'selected' : '' }}>DPA1 - FARMACIA PALIATIVOS</option>
                                <option value="EHU1" {{ request('cod_farmacia') == 'EHU1' ? 'selected' : '' }}>EHU1 - FARMACIA HUÉRFANAS</option>
                                <option value="EM01" {{ request('cod_farmacia') == 'EM01' ? 'selected' : '' }}>EM01 - FARMACIA EMCALI</option>
                                <option value="EVEN" {{ request('cod_farmacia') == 'EVEN' ? 'selected' : '' }}>EVEN - FARMACIA EVENTO</option>
                                <option value="EVSM" {{ request('cod_farmacia') == 'EVSM' ? 'selected' : '' }}>EVSM - EVENTO SALUD MENTAL</option>
                                <option value="FRJA" {{ request('cod_farmacia') == 'FRJA' ? 'selected' : '' }}>FRJA - FARMACIA JAMUNDÍ</option>
                                <option value="INY"  {{ request('cod_farmacia') == 'INY'  ? 'selected' : '' }}>INY - FARMACIA INYECTABLES</option>
                                <option value="PAC"  {{ request('cod_farmacia') == 'PAC'  ? 'selected' : '' }}>PAC - FARMACIA PAC</option>
                                <option value="SM01" {{ request('cod_farmacia') == 'SM01' ? 'selected' : '' }}>SM01 - FARMACIA SALUD MENTAL</option>
                                <option value="BPDT" {{ request('cod_farmacia') == 'BPDT' ? 'selected' : '' }}>BPDT - BOLSA</option>
                                <option value="FRIO" {{ request('cod_farmacia') == 'FRIO' ? 'selected' : '' }}>FRIO - FARMACIA IDEO</option>
                                <option value="EVIO" {{ request('cod_farmacia') == 'EVIO' ? 'selected' : '' }}>EVIO - EVENTO IDEO</option>
                                <option value="BDNT" {{ request('cod_farmacia') == 'BDNT' ? 'selected' : '' }}>BDNT - BOLSA NORTE</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="proveedor" class="font-weight-bold mb-1">
                                <i class="fas fa-file-invoice mr-1"></i>Proveedor
                            </label>
                            <input type="text" class="form-control form-control-sm" name="proveedor" value="{{ request('proveedor') }}" placeholder="Ej: Proveedor XYZ">
                        </div>
                        

                        <div class="form-group col-md-2 text-center">
                            <label class="d-block font-weight-bold mb-1">&nbsp;</label>
                            <button type="submit" id="buscarOrdenesBtn" class="btn btn-success btn-block">
                                <i class="fas fa-search mr-1"></i>Buscar
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
