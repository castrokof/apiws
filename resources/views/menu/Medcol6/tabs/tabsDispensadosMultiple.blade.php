<div class="row">
    <div class="col-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <!-- Pestaña 1: Detalle de la Factura -->
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-datos-med-dispensado-tab" data-toggle="pill"
                        href="#custom-tabs-one-datos-med-dispensado" data-target="#custom-tabs-one-datos-med-dispensado"
                         role="tab" aria-controls="custom-tabs-one-datos-med-dispensado" aria-selected="true">
                         Detalle de la Factura
                        </a>
                    </li>

                    <!-- Nueva Pestaña: Datos de la Fórmula -->
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-datos-formula-tab" data-toggle="pill"
                        href="#custom-tabs-one-datos-formula" data-target="#custom-tabs-one-datos-formula"
                         role="tab" aria-controls="custom-tabs-one-datos-formula" aria-selected="false">
                         Datos de la Fórmula
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <!-- Contenido del primer tab: Detalle de la Factura -->
                    <div class="tab-pane fade active show" id="custom-tabs-one-datos-med-dispensado" role="tabpanel"
                         aria-labelledby="custom-tabs-one-datos-med-dispensado-tab">
                        <div class="card-body">
                            @include('menu.Medcol6.form.dispensado.form')
                        </div>
                    </div>

                    <!-- Contenido del nuevo tab: Datos de la Fórmula -->
                    <div class="tab-pane fade" id="custom-tabs-one-datos-formula" role="tabpanel"
                         aria-labelledby="custom-tabs-one-datos-formula-tab">
                        <div class="card-body">
                            <!-- Aquí se incluye el nuevo formulario de Datos de la Fórmula -->
                            @include('menu.Medcol6.form.dispensado.datos_formula_form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

