<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')
        <div class="card card-info">
            <div class="card-header with-border">
                <h3 class="card-title">Listas documentos</h3>
                <div class="card-tools pull-right">
                    <button type="button" class="btn create_documento btn-default" name="create_documento" id="create_documento"><i
                            class="fa fa-fw fa-plus-circle"></i>Nuevo documento</button>
                </div>
            </div>
            <div class="card-body table-responsive p-2">

                <table id="documentos" class="table table-hover  text-nowrap">

                    <thead>
                        <tr>
                            <th>Acciones</th>
                            <th>Documento</th>
                            <th>Consecutivo</th>
                            <th>Observación</th>
                           

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
