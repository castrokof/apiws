<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')
        <div class="card card-info">
            <div class="card-header with-border">
                <h3 class="card-title">Cargue de Cotizaciones</h3>
                <div class="card-tools pull-right">
                    <button type="button" class="btn create_cotizacion btn-default" name="create_cotizacion" id="create_cotizacion"><i
                            class="fa fa-fw fa-plus-circle"></i>Cargar cotización</button>
                </div>
            </div>
            <div class="card-body table-responsive p-2">

                <table id="listasCotizaciones" class="table table-hover  text-nowrap">
                    <thead>
                        <tr>
                            <th>Acciones</th>
                            <th>Nombre Archivo</th>
                            <th>Cantidad de Filas</th>
                            <th>Fecha inicio</th>
                            <th>Fecha fin</th>
                            <th>Estado</th>
                                            
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
