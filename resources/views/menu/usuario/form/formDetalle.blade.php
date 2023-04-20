<div class="form-group row">
    <div class="col-lg-3">
        <label for="nombre1_n" class="col-xs-4 control-label requerido">Primer nombre</label>
        <input type="text" name="nombre1_n" id="nombre1_n" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="nombre2_n" class="col-xs-4 control-label ">Segundo nombre</label>
        <input type="text" name="nombre2_n" id="nombre2_n" class="form-control " readonly>
    </div>
    <div class="col-lg-3">
        <label for="apellido1_n" class="col-xs-4 control-label requerido">Primer apellido</label>
        <input type="text" name="apellido1_n" id="apellido1_n" class="form-control " readonly>
    </div>
    <div class="col-lg-3">
        <label for="apellido2_n" class="col-xs-4 control-label ">Segundo apellido</label>
        <input type="text" name="apellido2_n" id="apellido2_n" class="form-control " readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-2">
        <label for="Tipodocum_n" class="col-xs-4 control-label ">Tipo de documento</label>
        <input type="text" name="Tipodocum_n" id="Tipodocum_n" class="form-control " readonly>
    </div>
    <div class="col-lg-3">
        <label for="historia_n" class="col-xs-4 control-label requerido">Documento</label>
        <input type="text" name="historia_n" id="historia_n" class="form-control" minlength="5" readonly>
    </div>

    <div class="col-lg-1">
        <label for="cantedad_n" class="col-xs-4 control-label ">Edad</label>
        <input type="text" name="cantedad_n" id="cantedad_n" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="direcres_n" class="col-xs-4 control-label requerido">Direccion</label>
        <input type="text" name="direcres_n" id="direcres_n" class="form-control " minlength="6" readonly>
    </div>
    <div class="col-lg-3">
        <label for="telefres_n" class="col-xs-4 control-label requerido">Telefono</label>
        <input type="text" name="telefres_n" id="telefres_n" class="form-control" readonly>
    </div>

</div>
<div class="form-group row">
    <div class="col-lg-2">
        <label for="fecha_factura_n" class="col-xs-4 control-label requerido">Fecha Factura</label>
        <input type="date" name="fecha_factura_n" id="fecha_factura_n" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="documento_n" class="col-xs-4 control-label requerido">Comprobante</label>
        <input type="text" name="documento_n" id="documento_n" class="form-control " minlength="6" readonly>
    </div>
    <div class="col-lg-3">
        <label for="factura_n" class="col-xs-4 control-label requerido">Factura No.</label>
        <input type="text" name="factura_n" id="factura_n" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="cajero_n" class="col-xs-4 control-label requerido">Auxiliar</label>
        <input type="text" name="cajero_n" id="cajero_n" class="form-control" readonly>
    </div>

</div>
<div class="form-group row">
    <div class="col-lg-2">
        <label for="codigo_n" class="col-xs-4 control-label requerido">Código</label>
        <input type="text" name="codigo_n" id="codigo_n" class="form-control" readonly>
    </div>
    <div class="col-lg-6">
        <label for="nombre_n" class="col-xs-4 control-label requerido">Medicamento</label>
        <input type="text" name="nombre_n" id="nombre_n" class="form-control" readonly>
    </div>
    <div class="col-lg-2">
        <label for="cantidad_n" class="col-xs-4 control-label requerido">Cantidad</label>
        <input type="text" name="cantidad_n" id="cantidad_n" class="form-control" readonly>
    </div>
    <div class="col-lg-2">
        <label for="cums_n" class="col-xs-4 control-label requerido">Cód CUMS</label>
        <input type="text" name="cums_n" id="cums_n" class="form-control" readonly>
    </div>
</div>
<fieldset>
    <legend style="color: #28a745;">Detalle del documento pendiente</legend>
    <div class="form-group row">
        <div class="col-lg-2">
            <label for="cantord_n" class="col-xs-4 control-label requerido">Cantidad Ordenada</label>
            <input type="number" name="cantord_n" id="cantord_n" class="form-control" readonly>
        </div>
        <div class="col-lg-2">
            <label for="cantdpx_n" class="col-xs-4 control-label requerido">Cantidad Entregada</label>
            <input type="number" name="cantdpx_n" id="cantdpx_n" class="form-control" readonly>
        </div>
        <div class="col-lg-2">
            <label for="cant_pndt_n" class="col-xs-4 control-label requerido">Cantidad Pendiente</label>
            <input type="number" name="cant_pndt_n" id="cant_pndt_n" class="form-control" readonly>
        </div>
        <!-- <div id="futuro1" class="col-lg-3" style="display:none;">
            <label for="fecha_entrega_n" class="col-xs-4 control-label ">Fecha Entrega</label>
            <input type="date" name="fecha_entrega_n" id="fecha_entrega_n" class="form-control" readonly>
        </div>
        <div id="futuro2" class="col-lg-3" style="display:none;">
            <label for="fecha_impresion_n" class="col-xs-4 control-label ">Fecha Tramitado</label>
            <input type="date" name="fecha_impresion_n" id="fecha_impresion_n" class="form-control" readonly>
        </div>
        <div id="futuro3" class="col-lg-3" style="display:none;">
            <label for="fecha_n" class="col-xs-4 control-label ">Fecha Pendiente</label>
            <input type="date" name="fecha_n" id="fecha_n" class="form-control" readonly>
        </div>
        <div id="futuro4" class="col-lg-3" style="display:none;">
            <label for="fecha_anulado_n" class="col-xs-4 control-label ">Fecha Anulación</label>
            <input type="date" name="fecha_anulado_n" id="fecha_anulado_n" class="form-control" readonly>
        </div> -->
        <div class="col-lg-3" >
            <label for="fecha_estado" class="col-xs-4 control-label "> </label>
            <input type="date" name="fecha_estado" id="fecha_estado" class="form-control" readonly>
        </div>
        <div class="col-lg-3">
            <label for="estado_n" class="col-xs-4 control-label requerido">Estado</label>
            <input name="estado_n" id="estado_n" class="form-control" readonly>
        </div>
    </div>
</fieldset>
<div class="form-group row">
    <div class="col-lg-2">
        <label for="usuario_n" class="col-xs-4 control-label ">Usuario que registro</label>
        <div class="input-group">
            <input name="usuario_n" id="usuario_n" class="form-control" readonly>
            <!-- <span class="input-group-addon info-icon" style="color: #31df9d;"><i class="fa fa-info-circle"></i></span> -->
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="infoModalLabel">Información</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="infoText">El documento pendiente no tiene ningun trámite.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-target="infoModal">Cerrar</button>
            </div>
        </div>
    </div>
</div> -->
