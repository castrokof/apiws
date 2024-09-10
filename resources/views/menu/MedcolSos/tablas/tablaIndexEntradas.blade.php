<div class="row">
        <div class="col-lg-12">
            @include('includes.form-error')
            @include('includes.form-mensaje')
            <div class="card card-success">
                <div class="card-header with-border">
                    <h3 class="card-title-1">Liata Ordenes</h3>
                    <div class="card-tools pull-right">
                        <button type="button" class="btn btn-default" name="create_entrada" id="create_entrada"
                            data-toggle="modal" data-target="#modal-u"><i class="fa fa-fw fa-plus-circle"></i> Nueva orden</button>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive p-2">
                <table id="ordenes" class="table table-hover  text-nowrap">
                        <thead>
                            <tr>
                                <th>Acciones</th>
                                <th>Orden</th>
                                <th># Orden</th>
                                <th>Proveedor</th>
                                <th>Fecha Orden</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <!-- /.card-body -->
            </div>
        </div>
    </div>
