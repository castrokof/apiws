<div class="form-group row">
    <div class="col-lg-3">
        <label for="historia" class="control-label requerido">Historia</label>
        <input type="text" name="historia" id="historia" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="codigo" class="control-label requerido">Código</label>
        <input type="text" name="codigo" id="codigo" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="nombre_generico" class="control-label requerido">Medicamento / Insumo</label>
        <input type="text" name="nombre_generico" id="nombre_generico" class="form-control" readonly>
    </div>
</div>

<div class="form-group row">
    <div class="col-lg-3">
        <label for="fecha_ordenamiento" class="control-label requerido">Fecha Ordenamiento</label>
        <input type="date" name="fecha_ordenamiento" id="fecha_ordenamiento" class="form-control" readonly>
    </div>
    <div class="col-lg-3">
        <label for="fecha_suministro" class="control-label requerido">Fecha Suministro</label>
        <input type="date" name="fecha_suministro" id="fecha_suministro" class="form-control" readonly>
    </div>
    <div class="col-lg-4">
        <label for="estado" class="control-label requerido">Estado</label>
        <input type="text" name="estado" id="estado" class="form-control" readonly>
    </div>
</div>

<fieldset>
    <legend style="color: #31df9d;">Modifique los datos que corresponda</legend>
    <div class="form-group row">
        <div class="col-lg-3">
            <label for="autorizacion" class="control-label requerido">Autorizacion</label>
            <input type="number" name="autorizacion" id="autorizacion" class="form-control">
        </div>
        <div class="col-lg-3">
            <label for="mipres" class="control-label requerido">Numero de mipres</label>
            <input type="number" name="mipres" id="mipres" class="form-control">
        </div>
        <div class="col-lg-3">
            <label for="reporte_entrega_nopbs" class="control-label requerido">Reporte de Entrega NOPBS</label>
            <input type="number" name="reporte_entrega_nopbs" id="reporte_entrega_nopbs" class="form-control" >
        </div>
    </div>
</fieldset>

<div class="form-group row">
    
    <div class="col-lg-8">
        <label for="observacion" class="control-label requerido">Observaciones</label>
        <textarea name="observacion" id="observacion" class="form-control UpperCase" rows="3" placeholder="Ingrese las observaciones ..." required></textarea>
    </div>
</div>
