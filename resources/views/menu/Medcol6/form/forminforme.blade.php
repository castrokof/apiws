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
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalIndicadores">
                            <i class="fas fa-chart-bar mr-1"></i> Indicadores
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