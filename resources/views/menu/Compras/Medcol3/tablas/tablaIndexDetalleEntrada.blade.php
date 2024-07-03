@php
$iduser = Session()->get('usuario');
$id= Session()->get('usuario_id');
@endphp

<div class="card card-info p-2">
    <div>
        <!-- Modal -->
        <button type="button" class="btn btn-primary" name="agregar_articulo" id="agregar_articulo" data-toggle="modal" data-target="#modal-u"><i class="fa fa-plus-circle"></i> Agregar</button>
    </div>

    <div class="x_panel">
        <div class="card-body with-border">

            <div class="card-body table-responsive p-1">
                <table id="tcups" class="table table-hover table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>Acciones</th>
                            <th>Codigo Articulo</th>
                            <th>Articulo/Producto</th>
                            <th>Cums</th>
                            <th>Vlr Unitario</th>
                            <th>Iva%</th>
                            <th>Valor Iva Uni</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Ivatotal</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  
</div>
