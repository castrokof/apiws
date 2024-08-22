<div class="modal fade" tabindex="-1" id="modal-edit-pendientes" role="dialog" aria-labelledby="myLargeModalLabel" hidden.bs.modal="limpiarModal()">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="row">
                <div class="col-lg-12">
                    @include('includes.form-error')
                    @include('includes.form-mensaje')
                    <span id="form_result"></span>
                    <div class="card card-info" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
                        <div class="card-header with-border">
                            <h3 class="card-title" id="edit_pendiente"></h3>

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
                        <form id="form-general1" class="form-horizontal">
                            @csrf
                            <div class="card-body">
                                @include('menu.Medcol5.tabs.tabsIndexPendientes')
                            </div>
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
