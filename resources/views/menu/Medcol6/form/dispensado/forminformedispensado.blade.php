<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                @if(Auth::user()->drogueria == '1')
                <h1 class="m-0">Dispensado Medcol SOS - JAMUNDI</h1>
                @elseif(Auth::user()->drogueria == '4')
                <h1 class="m-0">Dispensado Medcol PCE</h1>
                @elseif(Auth::user()->drogueria == '5')
                <h1 class="m-0">Dispensado Medcol Huerfanas</h1>
                @elseif(Auth::user()->drogueria == '6')
                <h1 class="m-0">Dispensado Medcol BIOLOGICOS</h1>
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
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="fecha" class="col-form-label">Fecha inicial</label>
                                <input type="date" name="fechaini" id="fechaini" class="form-control" value="{{ old('fechaini') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="fechafin" class="col-form-label">Fecha final</label>
                                <input type="date" name="fechafin" id="fechafin" class="form-control" value="{{ old('fechafin') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="contrato" class="col-form-label">Droguería</label>
                                <select name="contrato" id="contrato" class="form-control select2bs4" style="width: 100%;" required>
                                    <option value="">Seleccione opcion...</option>
                                    <option value="FACO">FACO-FARMACIA SOS PASOANCHO</option>
                                    <option value="FAID">FAID-FARMACIA SOS IDEO</option>
                                    <option value="FAAU">FAAU-FARMACIA SOS AUTOPISTA</option>
                                    <option value="FAJA">FAJA-FARMACIA JAMUNDI COMFENALCO</option>
                                    <option value="EVEN">EVEN-FARMACIA EVENTO</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" name="reset" id="reset" class="btn btn-warning btn-block">Limpiar</button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" name="buscar" id="buscar" class="btn btn-success btn-block">Buscar</button>
                            </div>
                            <div class="col-md-12 mt-3">
                                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#gestion_multiple">
                                    Gestion Multiple
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4" id="detalle"></div>
                    <div class="col-md-4" id="detalle1"></div>
                    <div class="col-md-4" id="detalle2"></div>
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