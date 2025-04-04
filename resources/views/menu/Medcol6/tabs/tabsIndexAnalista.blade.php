<div class="row">
            <div class="col-md-8">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h2 class="m-0 text-dark">Ingrese Rango de Fechas</h2>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="fecha" class="col-form-label">Fecha inicial</label>
                                <input type="date" name="fechaini" id="fechaini" class="form-control" value="{{ old('fechaini') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="fechafin" class="col-form-label">Fecha final</label>
                                <input type="date" name="fechafin" id="fechafin" class="form-control" value="{{ old('fechafin') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="contrato" class="col-form-label">Farmacia</label>
                                <select name="contrato" id="contrato" class="form-control select2bs4" style="width: 100%;" required>
                                    <option value="">Seleccione opcion...</option>
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
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" name="reset" id="reset" class="btn btn-warning btn-block">Limpiar</button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" name="buscar" id="buscar" class="btn btn-primary btn-block">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <div class="col-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-datos-del-paciente-tab" data-toggle="pill" href="#custom-tabs-one-datos-del-paciente" role="tab" aria-controls="custom-tabs-one-datos-del-paciente" aria-selected="false">Pendientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-datos-agendados-tab" data-toggle="pill" href="#custom-tabs-one-datos-agendados" role="tab" aria-controls="custom-tabs-one-datos-agendados" aria-selected="false">En Tramite</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-datos-seguimiento-tab" data-toggle="pill" href="#custom-tabs-one-datos-seguimiento" role="tab" aria-controls="custom-tabs-one-datos-seguimiento" aria-selected="false">Entregados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-datos-desabastecido-tab" data-toggle="pill" href="#custom-tabs-one-datos-desabastecido" role="tab" aria-controls="custom-tabs-one-datos-desabastecido" aria-selected="false">Desabastecido</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-datos-anulado-tab" data-toggle="pill" href="#custom-tabs-one-datos-anulado" role="tab" aria-controls="custom-tabs-one-datos-anulado" aria-selected="false">Anulado</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-datos-vencidos-tab" data-toggle="pill" href="#custom-tabs-one-datos-vencidos" role="tab" aria-controls="custom-tabs-one-datos-vencidos" aria-selected="false">Vencidos</a>
                    </li>

                    <div class="card-tools pull-right">

                    </div>
                </ul>

            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-one-datos-del-paciente" role="tabpanel" aria-labelledby="custom-tabs-one-datos-del-paciente-tab">
                        <div class="card-body">
                            @include('menu.Medcol6.tablas.tablaIndexPendientes')
                        </div>
                    </div>

                    <div class="tab-pane fade " id="custom-tabs-one-datos-agendados" role="tabpanel" aria-labelledby="custom-tabs-one-datos-agendados-tab">
                        <div class="card-body">
                            @include('menu.Medcol6.tablas.tablaIndexTransito')
                        </div>

                    </div>

                    <div class="tab-pane fade " id="custom-tabs-one-datos-seguimiento" role="tabpanel" aria-labelledby="custom-tabs-one-datos-seguimiento-tab">
                        <div class="card-body">
                            @include('menu.Medcol6.tablas.tablaIndexEntragados')
                        </div>

                    </div>
                    <div class="tab-pane fade " id="custom-tabs-one-datos-desabastecido" role="tabpanel" aria-labelledby="custom-tabs-one-datos-desabastecido-tab">
                        <div class="card-body">
                            @include('menu.Medcol6.tablas.tablaIndexDesabastecido')
                        </div>

                    </div>
                    <div class="tab-pane fade " id="custom-tabs-one-datos-anulado" role="tabpanel" aria-labelledby="custom-tabs-one-datos-anulado-tab">
                        <div class="card-body">
                            @include('menu.Medcol6.tablas.tablaIndexAnulado')
                        </div>

                    </div>
                    <div class="tab-pane fade " id="custom-tabs-one-datos-vencidos" role="tabpanel" aria-labelledby="custom-tabs-one-datos-vencidos-tab">
                        <div class="card-body">
                            @include('menu.Medcol6.tablas.tablaIndexVencidos')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="btn-flotante-container">
        <button type="button" id="syncapi" class="btn-flotante tooltipsC" title="Sync Pendientes">
            <i class="fas fa-capsules fa-1x"></i>
            <span class="badge badge-pill pull-right">Sync Pendientes</span>
        </button>

        <button type="button" id="synanuladospndt" class="btn-flotante-second tooltipsC" title="Sync Anulados">
            <i class="fas fa-trash fa-1x"></i>
            <span class="badge badge-pill pull-left">Sync Anulados</span>
        </button>
    </div>

</div>
