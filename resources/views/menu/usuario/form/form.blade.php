<div class="form-group row">
    <div class="col-lg-3">
        <label for="nombre1" class="col-xs-4 control-label requerido">Primer nombre</label>
        <input type="text" name="nombre1" id="nombre1" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="nombre2" class="col-xs-4 control-label ">Segundo nombre</label>
        <input type="text" name="nombre2" id="nombre2" class="form-control " readonly>
    </div>
    <div class="col-lg-3">
        <label for="apellido1" class="col-xs-4 control-label requerido">Primer apellido</label>
        <input type="text" name="apellido1" id="apellido1" class="form-control " readonly>
    </div>
    <div class="col-lg-3">
        <label for="apellido2" class="col-xs-4 control-label ">Segundo apellido</label>
        <input type="text" name="apellido2" id="apellido2" class="form-control " readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-2">
        <label for="Tipodocum" class="col-xs-4 control-label ">Tipo de documento</label>
        <input type="text" name="Tipodocum" id="Tipodocum" class="form-control " readonly>
    </div>
    <div class="col-lg-3">
        <label for="historia" class="col-xs-4 control-label requerido">Documento</label>
        <input type="text" name="historia" id="historia" class="form-control" minlength="5" readonly>
    </div>

    <div class="col-lg-1">
        <label for="cantedad" class="col-xs-4 control-label ">Edad</label>
        <input type="text" name="cantedad" id="cantedad" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="direcres" class="col-xs-4 control-label requerido">Direccion</label>
        <input type="text" name="direcres" id="direcres" class="form-control " minlength="6" readonly>
    </div>
    <div class="col-lg-3">
        <label for="telefres" class="col-xs-4 control-label requerido">Telefono</label>
        <input type="text" name="telefres" id="telefres" class="form-control" readonly>
    </div>

</div>
<div class="form-group row">
    <div class="col-lg-2">
        <label for="fecha_factura" class="col-xs-4 control-label requerido">Fecha Factura</label>
        <input type="date" name="fecha_factura" id="fecha_factura" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="documento" class="col-xs-4 control-label requerido">Comprobante</label>
        <input type="text" name="documento" id="documento" class="form-control " minlength="6" readonly>
    </div>
    <div class="col-lg-3">
        <label for="factura" class="col-xs-4 control-label requerido">Factura No.</label>
        <input type="text" name="factura" id="factura" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="cajero" class="col-xs-4 control-label requerido">Auxiliar</label>
        <input type="text" name="cajero" id="cajero" class="form-control" readonly>
    </div>

</div>
<div class="form-group row">
    <div class="col-lg-2">
        <label for="codigo" class="col-xs-4 control-label requerido">Código</label>
        <input type="text" name="codigo" id="codigo" class="form-control" readonly>
    </div>
    <div class="col-lg-6">
        <label for="nombre" class="col-xs-4 control-label requerido">Medicamento</label>
        <input type="text" name="nombre" id="nombre" class="form-control" readonly>
    </div>
    <div class="col-lg-2">
        <label for="cantidad" class="col-xs-4 control-label requerido">Cantidad</label>
        <input type="text" name="cantidad" id="cantidad" class="form-control" readonly>
    </div>
    <div class="col-lg-2">
        <label for="cums" class="col-xs-4 control-label requerido">Cód CUMS</label>
        <input type="text" name="cums" id="cums" class="form-control" readonly>
    </div>
</div>
<fieldset>
    <legend style="color: #31df9d;">Gestionar el documento pendiente</legend>
    <div class="form-group row">
        <div class="col-lg-2">
            <label for="cantord" class="col-xs-4 control-label requerido">Cantidad Ordenada</label>
            <input type="number" name="cantord" id="cantord" class="form-control" readonly>
        </div>
        <div class="col-lg-2">
            <label for="cantdpx" class="col-xs-4 control-label requerido">Cantidad Entregada</label>
            <input type="number" name="cantdpx" id="cantdpx" class="form-control" readonly>
        </div>
        <div class="col-lg-2">
            <label for="cant_pndt" class="col-xs-4 control-label requerido">Cantidad Pendiente</label>
            <input type="number" name="cant_pndt" id="cant_pndt" class="form-control" readonly>
        </div>
        <div id="futuro1" class="col-lg-3" style="display:none;">
            <label for="fecha_entrega" class="col-xs-4 control-label ">Fecha Entrega</label>
            <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control">
            <input type="hidden" name="enviar_fecha_entrega" id="enviar_fecha_entrega" value="false">
        </div>
        <div id="futuro2" class="col-lg-3" style="display:none;">
            <label for="fecha_impresion" class="col-xs-4 control-label ">Fecha Tramitado</label>
            <input type="date" name="fecha_impresion" id="fecha_impresion" class="form-control">
            <input type="hidden" name="enviar_fecha_impresion" id="enviar_fecha_impresion" value="false">
        </div>
        <div id="futuro3" class="col-lg-3" style="display:none;">
            <label for="fecha" class="col-xs-4 control-label ">Fecha Pendiente</label>
            <input type="date" name="fecha" id="fecha" class="form-control" readonly>
        </div>
        <div id="futuro4" class="col-lg-3" style="display:none;">
            <label for="fecha_anulado" class="col-xs-4 control-label ">Fecha Anulación</label>
            <input type="date" name="fecha_anulado" id="fecha_anulado" class="form-control">
            <input type="hidden" name="enviar_fecha_anulado" id="enviar_fecha_anulado" value="false">
        </div>
        <div class="col-lg-3">
            <label for="estado" class="col-xs-4 control-label requerido">Estado</label>
            <select name="estado" id="estado" class="form-control select2bs4" style="width: 100%;" required>
                <option value="">---seleccione---</option>
                <option value="PENDIENTE">PENDIENTE</option>
                <option value="ENTREGADO">ENTREGADO</option>
                <option value="TRAMITADO">TRAMITADO</option>
                <option value="DESABASTECIDO">DESABASTECIDO</option>
                <option value="ANULADO">ANULADO</option>
            </select>
        </div>
    </div>
</fieldset>
<div class="form-group row">
    <div class="col-lg-3">
        <label for="doc_entrega" class="col-xs-4 control-label requerido">Doc Entrega</label>
        <input type="text" name="doc_entrega" id="doc_entrega" class="form-control " minlength="6" value="MED" readonly> </br>

        <label for="factura_entrega" class="col-xs-4 control-label requerido">Factura Entrega</label>
        <input type="text" name="factura_entrega" id="factura_entrega" class="form-control" placeholder="Número Factura Rfast..." >
        <input type="hidden" name="enviar_factura_entrega" id="enviar_factura_entrega" value="false">
    </div>
    <div class="col-lg-6 col-md-6 col-xs-6">
        <label for="observacion" class="col-xs-8 control-label requerido">Observaciones</label>
        <textarea name="observacion" id="observacion" class="form-control UpperCase" rows="5" placeholder="Ingrese las observaciones ..." required></textarea>
    </div>
    <div class="col-lg-2">
        <label for="name" class="col-xs-4 control-label ">Usuario que registro</label>
        <input name="name" id="name" class="form-control" value="{{ Auth::user()->name ?? '' }}" readonly>
    </div>
</div>
