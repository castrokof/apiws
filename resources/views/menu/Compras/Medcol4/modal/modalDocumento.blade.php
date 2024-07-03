<div class="modal fade" tabindex="-1" id="modal-cuenta" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="card-drawel" class="card card-success">
                            <div class="card-header">
                                <h3 id="cardtitle" class="card-title accent-dark"></h3>
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
                            <form id="form-generalc" class="form-horizontal" method="POST">
                                @csrf

                                    @include('menu.Compras.Medcol2.form.formdatoscuenta')

                                <!-- /.card-body -->
                               <div class="card-footer">

                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        @include('includes.boton-form-crear-empresa-empleado-usuario')
                                    </div>
                                </div>
                                <!-- /.card-footer -->
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
