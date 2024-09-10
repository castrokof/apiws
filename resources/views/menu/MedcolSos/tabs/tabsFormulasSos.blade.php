<div class="row">
    <div class="col-12">
        <div id="cardtabspro" class="card card-bg-dark card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-datos-del-entrada-tab" data-toggle="pill"
                            href="#custom-tabs-one-datos-del-entrada" role="tab"
                            aria-controls="custom-tabs-one-datos-del-entrada" aria-selected="false">Formulas Pacientes Sos</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <form id="form-general" class="form-horizontal" method="POST">
                        <div class="tab-pane fade active show" id="custom-tabs-one-datos-del-entrada" role="tabpanel"
                            aria-labelledby="custom-tabs-one-datos-del-entrada-tab">
                            <div class="card-body">


                                @include('menu.MedcolSos.form.formdatosbasicosf')

                            </div>
                        </div>


                    </form>
                </div>
            </div>
            <!-- /.card -->
        </div>

    </div>

</div>
