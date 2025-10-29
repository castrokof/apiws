<div class="content-header">
    <div class="container-fluid">
        <!-- <div class="row mb-2">
            <div class="col-sm-6">
                @if(Auth::user()->drogueria == '1')
                <h1 class="m-0">Dispensado Medcol</h1>
                @elseif(Auth::user()->drogueria == '4')
                <h1 class="m-0">Dispensado Medcol PCE</h1>
                @elseif(Auth::user()->drogueria == '5')
                <h1 class="m-0">Dispensado Medcol Huerfanas</h1>
                @elseif(Auth::user()->drogueria == '6')
                <h1 class="m-0">Dispensado Medcol BIOLOGICOS</h1>
                @elseif(Auth::user()->drogueria == '2')
                <h1 class="m-0">Dispensado Medcol COMFENALCO SALUD MENTAL</h1>
                @elseif(Auth::user()->drogueria == '3')
                <h1 class="m-0">Dispensado Medcol COMFENALCO DOLOR</h1>
                @elseif(Auth::user()->drogueria == '8')
                <h1 class="m-0">Dispensado Medcol SOS PAC AUTOPISTA</h1>
                @elseif(Auth::user()->drogueria == '12')
                <h1 class="m-0">Dispensado Medcol SOS EVENTO</h1>
                @elseif(Auth::user()->drogueria == '13')
                <h1 class="m-0">Dispensado Medcol JAMUNDI COMFENALCO</h1>
                @endif
            </div>
            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/medcol6/dispensado') }}">Dispensado</a></li>
                    <li class="breadcrumb-item active">Gestión</li>
                </ol>
            </div>
        </div> -->

        <div class="row">
            <div class="col-md-12">
                @csrf
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-filter mr-2"></i>
                            Filtros de Búsqueda
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros de fecha y selección -->
                        <div class="row">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="fechaini" class="form-label font-weight-semibold">
                                    <i class="far fa-calendar-alt text-primary mr-1"></i>
                                    Fecha Inicial
                                </label>
                                <input type="date" name="fechaini" id="fechaini" class="form-control" value="{{ old('fechaini') }}">
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="fechafin" class="form-label font-weight-semibold">
                                    <i class="far fa-calendar-check text-primary mr-1"></i>
                                    Fecha Final
                                </label>
                                <input type="date" name="fechafin" id="fechafin" class="form-control" value="{{ old('fechafin') }}">
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="contrato" class="form-label font-weight-semibold">
                                    <i class="fas fa-clinic-medical text-primary mr-1"></i>
                                    Droguería
                                </label>
                                <select name="contrato" id="contrato" class="form-control select2bs4">
                                    <option value="">Todas las farmacias</option>
                                    <optgroup label="Farmacias Principales">
                                        <option value="BIO1">BIO1-FARMACIA BIOLOGICOS</option>
                                        <option value="DLR1">DLR1-FARMACIA DOLOR</option>
                                        <option value="DPA1">DPA1-FARMACIA PALIATIVOS</option>
                                        <option value="EM01">EM01-FARMACIA EMCALI</option>
                                        <option value="EHU1">EHU1-FARMACIA HUERFANAS</option>
                                        <option value="FRJA">FRJA-FARMACIA JAMUNDI</option>
                                        <option value="INY">INY-FARMACIA INYECTABLES</option>
                                        <option value="PAC">PAC-FARMACIA PAC</option>
                                        <option value="SM01">SM01-FARMACIA SALUD MENTAL</option>
                                    </optgroup>
                                    <optgroup label="Farmacias Especializadas">
                                        <option value="BPDT">BPDT-BOLSA</option>
                                        <option value="BDNT">BDNT-BOLSA NORTE</option>
                                        <option value="EVIO">EVIO-EVENTO IDEO</option>
                                        <option value="EVEN">EVEN-FARMACIA EVENTO</option>
                                        <option value="EVSM">EVSM-EVENTO SALUD MENTAL</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="cobertura" class="form-label font-weight-semibold">
                                    <i class="fas fa-shield-alt text-primary mr-1"></i>
                                    Cobertura
                                </label>
                                <select name="cobertura" id="cobertura" class="form-control select2bs4">
                                    <option value="">Todas</option>
                                    <option value="1">PBS - POS</option>
                                    <option value="2">NOPBS - NOPOS</option>
                                </select>
                            </div>
                        </div>

                        <!-- Botones de acción en grid moderno -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="btn-toolbar justify-content-between" role="toolbar">
                                    <!-- Grupo: Filtros -->
                                    <div class="btn-group btn-group-sm flex-wrap" role="group">
                                        <button type="button" name="buscar" id="buscar" class="btn btn-success">
                                            <i class="fas fa-search mr-1"></i>
                                            Buscar
                                        </button>
                                        <button type="button" name="reset" id="reset" class="btn btn-outline-warning">
                                            <i class="fas fa-eraser mr-1"></i>
                                            Limpiar
                                        </button>
                                    </div>

                                    <!-- Grupo: Sincronización -->
                                    <div class="btn-group btn-group-sm flex-wrap" role="group">
                                        <button type="button" id="syncapidis" class="btn btn-outline-primary" title="Sincronizar dispensados desde Medcol Centralizado">
                                            <i class="fas fa-sync-alt mr-1"></i>
                                            Sync Dispensados
                                        </button>
                                        <button type="button" id="synanulados" class="btn btn-outline-danger" title="Sincronizar anulados desde Medcol Centralizado">
                                            <i class="fas fa-ban mr-1"></i>
                                            Sync Anulados
                                        </button>
                                    </div>

                                    <!-- Grupo: Acciones -->
                                    <div class="btn-group btn-group-sm flex-wrap" role="group">
                                        <a href="{{ route('medcol6.informes') }}" class="btn btn-info">
                                            <i class="fas fa-chart-bar mr-1"></i>
                                            Informes
                                        </a>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#gestion_multiple">
                                            <i class="fas fa-layer-group mr-1"></i>
                                            Gestión Múltiple
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ayuda contextual -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-light border mb-0" role="alert">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle text-info mr-1"></i>
                                        <strong>Sugerencia:</strong> Use los botones de sincronización para actualizar datos desde Medcol Centralizado antes de generar informes.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="col-md-6">
                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-2"></i>
                            Resumen Estadístico
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4" id="detalle"></div>
                            <div class="col-md-4" id="detalle1"></div>
                            <div class="col-md-4" id="detalle2"></div>
                        </div>
                        <p class="text-muted text-center small mb-0">
                            <i class="fas fa-info-circle mr-1"></i>
                            Los datos se actualizan según los filtros seleccionados
                        </p>
                    </div>
                </div>
            </div> -->

        </div>
    </div>
</div>


<!--
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   @if(Auth::user()->drogueria == '1')
                    <h1 class="m-0">Pendientes Medcol PCE, HUE Y BIO</h1>
                   @elseif(Auth::user()->drogueria == '4')  
                   <h1 class="m-0">Pendientes Medcol PCE</h1>
                   @elseif(Auth::user()->drogueria == '5')  
                   <h1 class="m-0">Pendientes Medcol Huerfanas</h1>
                   @elseif(Auth::user()->drogueria == '6') 
                   <h1 class="m-0">Pendientes Medcol BIOLOGICOS</h1>
                   @endif
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Pendientes v1</li>
                    </ol>
                </div>
            </div>
            @csrf
            

        </div>
    </div>


-->