<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')
        <div class="card card-info">
            <div class="card-header with-border">
                <h3 class="card-title">Comprobantes</h3>
                <div class="card-tools pull-right">
                    <button type="button" class="btn create_scann btn-default" name="create_scann" id="create_scann"><i
                            class="fa fa-fw fa-plus-circle"></i>Nuevo Scann</button>
                </div>
            </div>
            <div class="card-body table-responsive p-2">

                <table id="scann_api" class="table table-hover  text-nowrap">

                    <thead>
                        <tr>
                            <th>Acciones</th>
                            <th>DetalleURL</th>
                            <th>codigo</th>
                            <th>comprobante</th>
                            <th>orden</th>
                            <th>pdf</th>
                            <th>usuario</th>
                            <th>Fecha Creación</th>

                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            </form>

        </div>
    </div>
</div>
