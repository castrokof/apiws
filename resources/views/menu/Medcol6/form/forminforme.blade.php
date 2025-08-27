<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="col-sm-6">
                    @if(Auth::user()->drogueria == '1')
                    <h1 class="m-0">Pendientes Medcol Centralizado</h1>
                    @elseif(Auth::user()->drogueria == '4')
                    <h1 class="m-0">Pendientes Medcol PCE</h1>
                    @elseif(Auth::user()->drogueria == '5')
                    <h1 class="m-0">Pendientes Medcol Huerfanas</h1>
                    @elseif(Auth::user()->drogueria == '6')
                    <h1 class="m-0">Pendientes Medcol BIOLOGICOS</h1>
                    @elseif(Auth::user()->drogueria == '2')
                    <h1 class="m-0">Pendientes Medcol COMFENALCO SALUD MENTAL</h1>
                    @elseif(Auth::user()->drogueria == '3')
                    <h1 class="m-0">Pendientes Medcol COMFENALCO DOLOR Y PALIATIVOS</h1>
                    @elseif(Auth::user()->drogueria == '8')
                    <h1 class="m-0">Pendientes Medcol SOS PAC AUTOPISTA</h1>
                    @elseif(Auth::user()->drogueria == '12')
                    <h1 class="m-0">Pendientes Medcol SOS EVENTO</h1>
                    @elseif(Auth::user()->drogueria == '13')
                    <h1 class="m-0">Pendientes Medcol JAMUNDI COMFENALCO</h1>
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Pendientes v2</li>
                </ol>
            </div>
        </div>
        @csrf


    </div>
</div>


<section class="content">
    <div class="container-fluid">
        <div class="row d-none justify-content-start gx-2 gy-2">
            <div class="col-md-2 col-sm-4 col-6" id="detalle"></div>
            <div class="col-md-2 col-sm-4 col-6" id="detalle1"></div>
            <div class="col-md-2 col-sm-4 col-6" id="detalle2"></div>
            <div class="col-md-2 col-sm-4 col-6" id="detalle3"></div>
            <div class="col-md-2 col-sm-4 col-6" id="detalle5"></div>
        </div>
    </div>
</section>

<div class="row mb-3">
    <div class="col-md-8">
        @csrf
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="m-0 text-primary font-weight-bold">Control de Pendientes</h4>
                    <div class="d-flex">
                        <button type="button" id="generar-informe" class="btn btn-success mr-2">
                            <i class="fas fa-file-pdf mr-1"></i> Generar Informe
                        </button>
                        <button type="button" class="btn btn-info mr-2" data-toggle="modal" data-target="#modalIndicadores">
                            <i class="fas fa-chart-bar mr-1"></i> Indicadores
                        </button>
                        <button type="button" class="btn btn-warning mr-2" data-toggle="modal" data-target="#modalGestionPacientes">
                            <i class="fa fa-user-md mr-1"></i> Gestión por Paciente
                        </button>
                        <button type="button" class="btn btn-purple" data-toggle="modal" data-target="#modalGestionPendientes">
                            <i class="fas fa-sync-alt mr-1"></i> Gestión de Pendientes
                        </button>
                    </div>
                </div>

                <form id="filtro-form" onsubmit="return false;">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="fechaini" class="small font-weight-bold">Fecha inicial</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="date" name="fechaini" id="fechaini" class="form-control form-control-sm" value="{{ old('fechaini') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="fechafin" class="small font-weight-bold">Fecha final</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="date" name="fechafin" id="fechafin" class="form-control form-control-sm" value="{{ old('fechafin') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-8">
                            <div class="form-group">
                                <label for="contrato" class="small font-weight-bold">Farmacia</label>
                                <select name="contrato" id="contrato" class="form-control form-control-sm select2bs4" style="width: 100%;" required>
                                    <option value="">Seleccione opción...</option>
                                    <option value="BIO1">BIO1-FARMACIA BIOLOGICOS</option>
                                    <option value="DLR1">DLR1-FARMACIA DOLOR</option>
                                    <option value="DPA1">DPA1-FARMACIA PALIATIVOS</option>
                                    <option value="EHU1">EHU1-FARMACIA HUERFANAS</option>
                                    <option value="EM01">EM01-FARMACIA EMCALI</option>
                                    <option value="EVEN">EVEN-FARMACIA EVENTO</option>
                                    <option value="EVSM">EVSM-EVENTO SALUD MENTAL</option>
                                    <option value="FRJA">FRJA-FARMACIA JAMUNDI</option>
                                    <option value="INY">INY-FARMACIA INYECTABLES</option>
                                    <option value="PAC">PAC-FARMACIA PAC</option>
                                    <option value="SM01">SM01-FARMACIA SALUD MENTAL</option>
                                    <option value="BPDT">BPDT-BOLSA</option>
                                    <option value="BDNT">BDNT-BOLSA NORTE</option>
                                    <option value="EVIO">EVIO-EVENTO IDEO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 d-flex align-items-end">
                            <div class="btn-group w-100">
                                <button type="button" name="buscar" id="buscar" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search mr-1"></i> Buscar
                                </button>
                                <button type="button" name="reset" id="reset" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-redo-alt mr-1"></i> Limpiar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- Modal Gestión de Pendientes -->
<div class="modal fade" id="modalGestionPendientes" tabindex="-1" role="dialog" aria-labelledby="modalGestionPendientesLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-purple text-white">
                <h5 class="modal-title" id="modalGestionPendientesLabel">
                    <i class="fas fa-sync-alt mr-2"></i>Gestión de Pendientes - Validación y Entrega
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Filtros -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-filter mr-2"></i>Filtros de Búsqueda</h6>
                    </div>
                    <div class="card-body">
                        <form id="filtroGestionForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fechaInicialGestion" class="small font-weight-bold">Fecha Inicial</label>
                                        <input type="date" id="fechaInicialGestion" name="fechaInicialGestion" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fechaFinalGestion" class="small font-weight-bold">Fecha Final</label>
                                        <input type="date" id="fechaFinalGestion" name="fechaFinalGestion" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="farmaciaGestion" class="small font-weight-bold">Farmacia</label>
                                        <select id="farmaciaGestion" name="farmaciaGestion" class="form-control form-control-sm">
                                            <option value="">Seleccione farmacia...</option>
                                            <option value="BIO1">BIO1-FARMACIA BIOLOGICOS</option>
                                            <option value="DLR1">DLR1-FARMACIA DOLOR</option>
                                            <option value="DPA1">DPA1-FARMACIA PALIATIVOS</option>
                                            <option value="EHU1">EHU1-FARMACIA HUERFANAS</option>
                                            <option value="EM01">EM01-FARMACIA EMCALI</option>
                                            <option value="EVEN">EVEN-FARMACIA EVENTO</option>
                                            <option value="EVSM">EVSM-EVENTO SALUD MENTAL</option>
                                            <option value="FRJA">FRJA-FARMACIA JAMUNDI</option>
                                            <option value="INY">INY-FARMACIA INYECTABLES</option>
                                            <option value="PAC">PAC-FARMACIA PAC</option>
                                            <option value="SM01">SM01-FARMACIA SALUD MENTAL</option>
                                            <option value="BPDT">BPDT-BOLSA</option>
                                            <option value="BDNT">BDNT-BOLSA NORTE</option>
                                            <option value="EVIO">EVIO-EVENTO IDEO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" id="buscarPendientes" class="btn btn-primary btn-sm btn-block">
                                        <i class="fas fa-search mr-1"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Área de resultados -->
                <div id="resultadosGestion" style="display: none;">
                    <!-- Controles de selección masiva -->
                    <div class="card mb-3">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="button" id="seleccionarTodos" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-check-square mr-1"></i> Seleccionar Todos
                                    </button>
                                    <button type="button" id="deseleccionarTodos" class="btn btn-outline-secondary btn-sm ml-2">
                                        <i class="fas fa-square mr-1"></i> Deseleccionar Todos
                                    </button>
                                </div>
                                <div>
                                    <span id="contadorSeleccionados" class="badge badge-info">0 seleccionados</span>
                                    <button type="button" id="procesarEntregas" class="btn btn-success btn-sm ml-2" disabled>
                                        <i class="fas fa-check-circle mr-1"></i> Procesar Entregas
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <div class="table-responsive">
                        <table id="tablaPendientesGestion" class="table table-striped table-bordered table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="40px">
                                        <div class="form-check">
                                            <input type="checkbox" id="checkboxAll" class="form-check-input">
                                        </div>
                                    </th>
                                    <th>Fecha Pendiente</th>
                                    <th>Historia</th>
                                    <th>Paciente</th>
                                    <th>Código</th>
                                    <th>Medicamento</th>
                                    <th>Cant. Pendiente</th>
                                    <th>Estado Actual</th>
                                    <th>Fecha Suministro</th>
                                    <th>Cant. Dispensada</th>
                                    <th>Estado Dispensado</th>
                                    <th>Factura</th>
                                    <th>No. Pendiente</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Loading spinner -->
                <div id="loadingGestion" class="text-center" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                    <p class="mt-2">Procesando datos...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
.btn-purple {
    background-color: #6f42c1;
    border-color: #6f42c1;
    color: white;
}
.btn-purple:hover {
    background-color: #5a36a3;
    border-color: #5a36a3;
    color: white;
}
.bg-purple {
    background-color: #6f42c1 !important;
}
</style>