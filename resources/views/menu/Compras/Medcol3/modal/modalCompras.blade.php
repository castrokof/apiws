<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')
        <span id="form_result"></span>
        <div id="card-drawel" class="card card-info">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Dolor - Generar Ordenes</h3>
                <button type="button" class="btn btn-primary ml-auto" name="importar_orden" id="importar_orden" data-toggle="modal" data-target="#importarModal"><i class="fa fa-plus-circle"></i> Importar</button>
            </div>
            <form id="form-general" class="form-horizontal" method="POST">
                @csrf
                <div class="card-body">
                    @include('menu.Compras.Medcol3.tabs.tabsingresos')
                </div>

                <button type="button" class="btn-flotante tooltipsC" id="guardar_entrada" title="Guardar ordenes"><i class="fas fa-save fa-2x"></i></button>
            </form>
        </div>
    </div>
</div>

<!-- Modal para importar datos -->
<div class="modal fade" id="importarModal" tabindex="-1" role="dialog" aria-labelledby="importarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importarModalLabel">Importar Ordenes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-importar" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Seleccionar archivo</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Importar</button>
                </div>
            </form>
        </div>
    </div>
</div>
