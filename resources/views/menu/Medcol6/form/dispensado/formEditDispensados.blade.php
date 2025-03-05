<div class="form-group row">
    <div class="col-md-6">
        <label for="nombre_completo">Paciente</label>
        <input type="text" id="nombre_completo" class="form-control" readonly>
    </div>
    <div class="col-lg-1">
        <label for="tipodocument" class="col-xs-4 control-label ">Identificación</label>
        <input type="text" name="tipodocument" id="tipodocument" class="form-control " readonly>
    </div>
    <div class="col-lg-2">
        <label for="historia" class="col-xs-4 control-label requerido">Historia</label>
        <input type="text" name="historia" id="historia" class="form-control" minlength="5" readonly>
    </div>
    <div class="col-lg-1">
        <label for="numeroIdentificacion" class="col-xs-4 control-label ">Número ID</label>
        <input type="text" name="numeroIdentificacion" id="numeroIdentificacion" class="form-control" readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-5">
        <label for="ciudad" class="col-xs-4 control-label requerido">Ciudad</label>
        <input type="text" name="ciudad" id="ciudad" class="form-control " minlength="6" readonly>
    </div>
    <div class="col-lg-2">
        <label for="factura" class="col-xs-4 control-label requerido">Factura</label>
        <input type="text" name="factura" id="factura" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="codigo" class="col-xs-4 control-label requerido">Código</label>
        <input type="text" name="codigo" id="codigo" class="form-control" minlength="6" readonly>
    </div>
    <div class="col-lg-2">
        <label for="nombre_comercial" class="col-xs-4 control-label requerido">Nombre Comercial</label>
        <input type="text" name="nombre_comercial" id="nombre_comercial" class="form-control" readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-2">
        <label for="fecha_ordenamiento" class="col-xs-4 control-label requerido">Fecha Ordenamiento</label>
        <input type="date" name="fecha_ordenamiento" id="fecha_ordenamiento" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="fecha_suministro" class="col-xs-4 control-label requerido">Fecha Suministro</label>
        <input type="date" name="fecha_suministro" id="fecha_suministro" class="form-control" readonly>
    </div>
    <div class="col-lg-2">
        <label for="cums" class="col-xs-4 control-label requerido">CUMS</label>
        <input type="text" name="cums" id="cums" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="centroprod" class="col-xs-4 control-label requerido">Centro Producción</label>
        <input type="text" name="centroprod" id="centroprod" class="form-control" readonly>
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-8">
        <label for="reporte_entrega_nopbs" class="col-xs-4 control-label requerido">Observaciones</label>
        <input type="text" name="reporte_entrega_nopbs" id="reporte_entrega_nopbs" class="form-control" readonly>
    </div>
    <div class="col-lg-4">
        <label for="estado" class="col-xs-4 control-label requerido">Estado</label>
        <input type="text" name="estado" id="estado" class="form-control" readonly>
    </div>
</div>
<fieldset>
    <legend style="color: #31df9d;">Gestionar el documento pendiente</legend>
    <div class="form-group row">
        <div class="col-lg-2">
            <label for="cantidad_ordenada" class="col-xs-4 control-label requerido">Cantidad Ordenada</label>
            <input type="number" name="cantidad_ordenada" id="cantidad_ordenada" class="form-control">
        </div>
        <div class="col-lg-2">
            <label for="numero_unidades" class="col-xs-4 control-label requerido">Número Unidades</label>
            <input type="number" name="numero_unidades" id="numero_unidades" class="form-control">
        </div>
        <div class="col-lg-3">
            <label for="numero_entrega" class="col-xs-4 control-label requerido">Número Entrega</label>
            <input type="text" name="numero_entrega" id="numero_entrega" class="form-control" readonly>
        </div>
    </div>
</fieldset>
<div class="form-group row">
    <div class="col-lg-2">
        <label for="name" class="col-xs-4 control-label">Usuario que registró</label>
        <input name="name" id="name" class="form-control" value="{{ Auth::user()->name ?? '' }}" readonly>
    </div>
    <div class="col-lg-8 col-md-6 col-xs-6">
        <label for="observacion" class="col-xs-8 control-label requerido">Observaciones</label>
        <textarea name="observacion" id="observacion" class="form-control UpperCase" rows="5" placeholder="Ingrese las observaciones ..." required></textarea>
    </div>
</div>
