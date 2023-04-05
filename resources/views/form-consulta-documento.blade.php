<div class="form-group row">
    <div class="col-lg-3">
        <label for="fecha" class="col-xs-4 control-label ">Fecha inicial</label>
        <input type="date" name="fechaini" id="fechaini" class="form-control" value="{{old('fechaini')}}" >
    </div>
    <div class="col-lg-3">
        <label for="snombre" class="col-xs-4 control-label ">Fecha final</label>
        <input type="date" name="fechafin" id="fechafin" class="form-control" value="{{old('fechafin')}}" >
    </div>
    <div class="col-lg-3">
        <label for="tipo_documento" class="col-xs-4 control-label requerido">Tipo de documento</label>
        <select name="tipo_documento" id="tipo_documento" class="form-control select2bs4" style="width: 100%;" required>
            <option value="">---seleccione---</option>
            <option value="CC">CC</option>
            <option value="CE">CE</option>
            <option value="MS">MS</option>
            <option value="NI">NI</option>
            <option value="NU">NU</option>
            <option value="PE">PE</option>
            <option value="RC">RC</option>
            <option value="TI">TI</option>
        </select>
    </div>
    <div class="col-lg-3">
        <label for="documento" class="col-xs-4 control-label requerido">Documento</label>
        <input type="text" name="documento" id="documento" class="form-control" value="{{old('documento')}}" minlength="6"  required >
    </div>

</div>




