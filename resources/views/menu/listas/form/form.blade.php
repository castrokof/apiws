<div class="form-group row">
    <div class="col-lg-4">
        <label for="slug" class="col-xs-4 control-label requerido">Slug-Código</label>
        <input type="text" name="slug" id="slug" class="form-control UpperCase" value="{{old('slug')}}" required >
        <br>
        <label for="nombre" class="col-xs-4 control-label requerido">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control UpperCase" value="{{old('nombre')}}" required >
    </div>

    <div class="col-lg-6">
        <label for="descripcion" class="col-xs-4 control-label requerido">Descripción</label>
        <textarea name="descripcion" id="descripcion" class="form-control UpperCase" rows="4" placeholder="Ingrese la descripcion de la lista..." value="{{old('descripcion')}}" required></textarea>
    </div>
    <div class="col-lg-2" style="display: block;">
        <label for="customSwitch1" class="col-xs-4 control-label requerido">Desactivar/Activar</label><br>
        <div class="form-group">
            <div class="custom-control custom-switch custom-switch-off-ligth custom-switch-on-success">
            <input type="checkbox" class="custom-control-input form-control" id="customSwitch1" checked>
            <label class="custom-control-label" for="customSwitch1"></label>
            </div>
        </div>


    </div>
    <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{Auth::user()->id}}" >
    <input type="hidden" name="activo" id="activo" class="form-control" value="SI">
</div>







