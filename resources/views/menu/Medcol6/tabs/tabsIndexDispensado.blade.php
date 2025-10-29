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
                            @include('menu.Medcol6.tablas.dispensados.tablaIndexDispensados')
                        </div>
                    </div>

                    <div class="tab-pane fade " id="custom-tabs-one-datos-disrevisado" role="tabpanel" aria-labelledby="custom-tabs-one-datos-disrevisado-tab">
                        <div class="card-body">
                            @include('menu.Medcol6.tablas.dispensados.tablaIndexRevisados')
                        </div>

                    </div>
                    <div class="tab-pane fade " id="custom-tabs-one-datos-disanulado" role="tabpanel" aria-labelledby="custom-tabs-one-datos-disanulado-tab">
                        <div class="card-body">
                            @include('menu.Medcol6.tablas.dispensados.tablaIndexAnulados')
                        </div>
                    </div>
                    <!--<div class="tab-pane fade " id="custom-tabs-one-datos-desabastecido" role="tabpanel" aria-labelledby="custom-tabs-one-datos-desabastecido-tab">
                        <div class="card-body">
                            @include('menu.Medcol6.tablas.tablaIndexDesabastecido')
                        </div>

                    </div>
                    <div class="tab-pane fade " id="custom-tabs-one-datos-anulado" role="tabpanel" aria-labelledby="custom-tabs-one-datos-anulado-tab">
                        <div class="card-body">
                            @include('menu.Medcol6.tablas.tablaIndexAnulado')
                        </div>

                    </div>-->
                </div>
            </div>
        </div>
    </div>
</div>
