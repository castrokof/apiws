

<div class="form-group row">
            <div class="col-lg-3">
                <label for="nombrecuenta" class="col-xs-4 control-label requerido">Nombre Cuenta</label>
                <input type="text" name="nombrecuenta" id="nombrecuenta" class="form-control" value="{{ old('nombrecuenta') }}" required>
            </div>

            <div class="col-lg-3">
                <label for="sede_id" class="col-xs-4 control-label requerido"><i class="fas fa-map"> Sede IPS</i></label>
                <select name="sede_id" id="sede_id" class="form-control select2bs4" style="width: 100%;" required>
                </select>
            </div>
            <div class="col-lg-6">
                <label for="observacion" class="col-xs-8 control-label ">Observación</label>
            <textarea name="observacion" id="observacion" class="form-control" rows="3" placeholder="Enter ..."></textarea>
            </div>

        </div>

        <input type="hidden" name="user_id" id="user_idc" class="form-control" value="{{Session()->get('usuario_id')}}">
