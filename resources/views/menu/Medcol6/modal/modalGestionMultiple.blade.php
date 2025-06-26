<!-- Modal -->
<div class="modal fade" id="gestion_multiple" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-custom-size" role="document">
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
                                @include('menu.Medcol6.tabs.tabsDispensadosMultiple')
                            </div>
                            <!-- /.card-body -->
                            
                            <!-- /.card-footer -->
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS personalizado para hacer más grande el modal -->
<style>
    .modal-custom-size {
        max-width: 90%; /* Ajusta el ancho */
        height: 90%; /* Ajusta la altura */
    }
    .modal-content {
        height: 100%; /* Hacer que el contenido del modal ocupe todo el espacio */
    }
</style>
