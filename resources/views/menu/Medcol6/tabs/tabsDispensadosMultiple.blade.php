<div class="row">
    <div class="col-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-datos-med-dispensado-tab" data-toggle="pill"
                        href="#custom-tabs-one-datos-med-dispensado" data-target="#custom-tabs-one-datos-med-dispensado"
                         role="tab" aria-controls="custom-tabs-one-datos-med-dispensado" aria-selected="true">Detalle de la Factura</a>
                    </li>
                    
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-one-datos-med-dispensado" role="tabpanel" aria-labelledby="custom-tabs-one-datos-med-dispensado-tab">
                        <div class="card-body">
                            @include('menu.Medcol6.form.dispensado.form')
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
