<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @if(Auth::user()->drogueria == '1')
                <h1 class="m-0">Dispensado Medcol All</h1>
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
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dispensado v1</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h2 class="m-0 text-dark">Ingrese Rango de Fechas</h2>
                        <div class="form-group row align-items-end">
                            <div class="col-md-3">
                                <label for="fechaini" class="col-form-label">Fecha inicial</label>
                                <input type="date" name="fechaini" id="fechaini" class="form-control" value="{{ old('fechaini') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="fechafin" class="col-form-label">Fecha final</label>
                                <input type="date" name="fechafin" id="fechafin" class="form-control" value="{{ old('fechafin') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="contrato" class="col-form-label">Droguería</label>
                                <select name="contrato" id="contrato" class="form-control select2bs4" required>
                                    <option value="">Seleccione opción...</option>
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
                            <div class="col-md-3">
                                <label for="cobertura" class="col-form-label">Cobertura</label>
                                <select name="cobertura" id="cobertura" class="form-control select2bs4" required>
                                    <option value="">Seleccione opción...</option>
                                    <option value="1">PBS - POS</option>
                                    <option value="2">NOPBS - NOPOS</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-3">
                            <div class="col-md-3">
                                <button type="submit" name="reset" id="reset" class="btn btn-warning btn-block">Limpiar</button>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" name="buscar" id="buscar" class="btn btn-success btn-block">Buscar</button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-info btn-block" data-toggle="modal" data-target="#modal_generar_informe">
                                    Generar Informe
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#gestion_multiple">
                                    Gestión Múltiple
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <button class="btn btn-info btn-block mb-2" type="button" data-toggle="collapse" data-target="#resumenPanel" aria-expanded="false" aria-controls="resumenPanel">
                    Ver resumen
                </button>

                <div class="collapse" id="resumenPanel">
                    <div class="card card-body">
                        <div class="row">
                            <div class="col-md-4" id="detalle"></div>
                            <div class="col-md-4" id="detalle1"></div>
                            <div class="col-md-4" id="detalle2"></div>
                        </div>
                    </div>
                </div>
            </div>

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