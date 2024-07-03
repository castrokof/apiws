<div class="modal fade" tabindex="-1" id="modal-listas-detalle" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header with-border">
                            <h3 class="card-title" id="title-listas-detalle"></h3>

                            <div class="card-tools">
                                <button type="button" class="btn create_items btn-default" name="create_items"
                                    id="create_items"><i class="fa fa-fw fa-plus-circle"></i>Nuevo Item
                                </button>
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
                            @include('menu.Cotizaciones.Medcol3.tablas.tablaIndexListasDetalle')


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

