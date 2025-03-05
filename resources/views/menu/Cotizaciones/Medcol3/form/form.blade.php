<div class="form-group row">

  <div class="col-lg-3">
    <label for="fecha_inicio" class="col-xs-4 control-label requerido">Fecha-Inicio</label>
    <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control UpperCase" value="{{old('fecha_inicio')}}" required>
  </div>
  <div class="col-lg-3">
    <label for="fecha_fin" class="col-xs-4 control-label requerido">Fecha-Fin</label>
    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control UpperCase" value="{{old('fecha_fin')}}" required>
  </div>
  <div class="col-lg-6">
    <label for="file" class="col-xs-4 control-label requerido">Busca el archivo de cotizaciones</label>
    <input type="file" name="file" id="file">
  </div>

</div>



