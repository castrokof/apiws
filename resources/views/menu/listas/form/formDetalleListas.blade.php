<div class="form-group row">
    <div class="col-lg-4">
        <label for="slug1" class="col-xs-4 control-label requerido">Slug1-Código</label>
        <input type="text" name="slug" id="slug1" class="form-control UpperCase" value="{{old('surname')}}" required >
        <br>
        <label for="nombre1" class="col-xs-4 control-label requerido">Nombre1</label>
        <input type="text" name="nombre" id="nombre1" class="form-control UpperCase" value="{{old('ssurname')}}" required >
    </div>

    <div class="col-lg-6">
        <label for="descripcion1" class="col-xs-4 control-label requerido">Descripción</label>
        <textarea name="descripcion" id="descripcion1" class="form-control UpperCase" rows="4" placeholder="Ingrese la descripcion1 de la lista..." value="{{old('descripcion')}}" required></textarea>
    </div>
    <div class="col-lg-2" style="display: block;">
        <label for="customSwitch2" class="col-xs-4 control-label requerido">Desactivar/Activar</label><br>
        <div class="form-group">
            <div class="custom-control custom-switch custom-switch-off-ligth custom-switch-on-success">
            <input type="checkbox" class="custom-control-input form-control" id="customSwitch2" checked>
            <label class="custom-control-label" for="customSwitch2"></label>
            </div>
        </div>


    </div>
    <input type="hidden" name="user_id" id="user_id1" class="form-control" value="{{Auth::user()->id}}" >
    <input type="hidden" name="listas_id" id="list_id" class="form-control" value="" >
    <input type="hidden" name="activo" id="activo1" class="form-control" value="SI">
</div>







