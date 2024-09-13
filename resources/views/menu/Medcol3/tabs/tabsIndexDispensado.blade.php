<div class="row">
    <div class="col-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-datos-de-dispensado-tab" data-toggle="pill" href="#custom-tabs-one-datos-de-dispensado" role="tab" aria-controls="custom-tabs-one-datos-de-dispensado" aria-selected="false">Dispensados</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-datos-disrevisado-tab" data-toggle="pill" href="#custom-tabs-one-datos-disrevisado" role="tab" aria-controls="custom-tabs-one-datos-disrevisado" aria-selected="false">Revisados</a>
                    </li>
                   <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-datos-disanulado-tab" data-toggle="pill" href="#custom-tabs-one-datos-disanulado" role="tab" aria-controls="custom-tabs-one-datos-disanulado" aria-selected="false">Anulados</a>
                    </li>

                    <div class="card-tools pull-right">

                    </div>
                </ul>

            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-one-datos-de-dispensado" role="tabpanel" aria-labelledby="custom-tabs-one-datos-de-dispensado-tab">
                        <div class="card-body">
                            @include('menu.Medcol3.tablas.dispensados.tablaIndexDispensados')
                        </div>
                    </div>

                    <div class="tab-pane fade " id="custom-tabs-one-datos-disrevisado" role="tabpanel" aria-labelledby="custom-tabs-one-datos-disrevisado-tab">
                        <div class="card-body">
                            @include('menu.Medcol3.tablas.dispensados.tablaIndexRevisados')
                        </div>

                    </div>
                    <div class="tab-pane fade " id="custom-tabs-one-datos-disanulado" role="tabpanel" aria-labelledby="custom-tabs-one-datos-disanulado-tab">
                        <div class="card-body">
                            @include('menu.Medcol3.tablas.dispensados.tablaIndexAnulados')
                        </div>
                    </div>
                    <!--<div class="tab-pane fade " id="custom-tabs-one-datos-desabastecido" role="tabpanel" aria-labelledby="custom-tabs-one-datos-desabastecido-tab">
                        <div class="card-body">
                            @include('menu.Medcol3.tablas.tablaIndexDesabastecido')
                        </div>

                    </div>
                    <div class="tab-pane fade " id="custom-tabs-one-datos-anulado" role="tabpanel" aria-labelledby="custom-tabs-one-datos-anulado-tab">
                        <div class="card-body">
                            @include('menu.Medcol3.tablas.tablaIndexAnulado')
                        </div>

                    </div>-->
                </div>
            </div>
        </div>
    </div>
    <div class="btn-flotante-container">
        <button type="button" id="syncapidis" class="btn-flotante tooltipsC" title="Sync Dispensados">
            <i class="fas fa-capsules fa-1x"></i>
            <span class="badge badge-pill pull-right">Sync Dispensados</span>
        </button>
        <button type="button" id="synanulados" class="btn-flotante-second tooltipsC" title="Sync Anulados">
            <i class="fas fa-trash fa-1x"></i>
            <span class="badge badge-pill pull-left">Sync Anulados</span>
        </button>
    </div>

    <button type="button" id="syncdis" class="btn-flotante1 tooltipsC" title="Enviar Dispensados">
        <i class="fas fa-capsules fa-2x"></i>
        <span class="badge badge-pill pull-right">Enviar Dispensados</span>
    </button>


</div>
