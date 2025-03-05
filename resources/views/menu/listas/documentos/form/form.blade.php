<div class="form-group row">
    <div class="col-lg-4">
        <label for="documento" class="col-xs-4 control-label requerido">Documento</label>
        <input type="text" name="documento" id="documento" class="form-control UpperCase" value="{{old('documento')}}" required >
        <br>
        <label for="consecutivo" class="col-xs-4 control-label requerido">Consecutivo</label>
        <input type="number" name="consecutivo" id="consecutivo" class="form-control UpperCase" value="{{old('consecutivo')}}" required >
    </div>

    <div class="col-lg-6">
        <label for="observacion" class="col-xs-4 control-label requerido">Observación</label>
        <textarea name="observacion" id="observacion" class="form-control UpperCase" rows="4" placeholder="Ingrese la Observación de la lista..." value="{{old('descripcion')}}" required></textarea>
    </div>
   
    <input type="hidden" name="user_id" id="user_id" class="form-control" value=" {{ Auth::user()->id }}" >
  
</div>







