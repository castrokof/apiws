<div class="row">
    <div class="col-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-datos-med-pendiente-tab" data-toggle="pill"
                        href="#custom-tabs-one-datos-med-pendiente" data-target="#custom-tabs-one-datos-med-pendiente"
                         role="tab" aria-controls="custom-tabs-one-datos-med-pendiente" aria-selected="true">Detalle de la Factura</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-datos-observaciones-tab" data-toggle="pill"
                        href="#custom-tabs-one-datos-observaciones" data-target="#custom-tabs-one-datos-observaciones"
                        role="tab" aria-controls="custom-tabs-one-datos-observaciones" aria-selected="false">Observaciones</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-one-datos-med-pendiente" role="tabpanel" aria-labelledby="custom-tabs-one-datos-med-pendiente-tab">
                        <div class="card-body">
                            @include('menu.Medcol5.form.form')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-datos-observaciones" role="tabpanel" aria-labelledby="custom-tabs-one-datos-observaciones-tab">
                        <div class="card-body">
                            @include('menu.Medcol5.tablas.tablaObservaciones')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
