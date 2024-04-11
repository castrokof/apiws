<div class="modal fade" tabindex="-1" id="modal-items" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-warning">
                        <div class="card-header with-border">
                            <h3 class="card-title">Items</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>

                                <button type="button" class="btn btn-tool" data-dismiss="modal">
                                    <i class="fas fa-times"></i>
                                </button>


                            </div>
                        </div>
                        <div class="card-body">
                            <form id="form-general2" class="form-horizontal" method="POST">
                                @csrf
                                @include('menu.listas.form.formDetalleListas')

                                @include('includes.boton-form-crear-empresa-empleado-usuario')

                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
