<div class="form-group row">
     <div class="col-lg-4">
    <label for="codigo" class="col-xs-4 control-label ">Código de Articulo</label>
    <select name="codigo" id="codigo" class="form-control select2bs4" style="width: 900px;" required>
    </select>
    <small for="molecula" class="text-muted">Código de Articulo</small>
    <input type="hidden" name="molecula" id="molecula" class="form-control" >
  </div>
</div>
<div class="form-group row">
 
  <div class="col-lg-2">
    <label for="marca" class="col-xs-6 control-label requerido">Marca</label>
    <input type="text" name="marca" id="marca" class="form-control" value="{{old('marca')}} " readonly>
    </select>
  </div>

  <div class="col-lg-6">
    <label for="nombrea" class="col-xs-6 control-label requerido">Nombre articulo</label>
    <input type="text" name="nombrea" id="nombrea" class="form-control" value="{{old('nombrea')}} " readonly>
  </div>

  <div class="col-lg-6">
    <label for="cums" class="col-xs-6 control-label requerido">Cums</label>
    <input type="text" name="cums" id="cums" class="form-control" value="{{old('cums')}} " readonly>
    </select>
  </div>
  <div class="col-lg-3">
    <label for="presentacion" class="col-xs-6 control-label requerido">Presenetación</label>
    <input type="text" name="presentacion" id="presentacion" class="form-control" value="{{old('presentacion')}}" readonly>
    </select>
  </div>
</div>
<div class="form-group row">

  
  <div class="col-lg-2">
    <label for="cantidad" class="col-xs-4 control-label requerido">Cantidad</label>
    <input type="number" name="cantidad" id="cantidad" class="form-control" value="{{old('cantidad')}}">
  </div>
  <div class="col-lg-3">
    <label for="precio_compra_subtotal_unitario" class="col-xs-4 control-label  requerido">Valor Unitario</label>
    <input type="number" name="precio_compra_subtotal_unitario" id="precio_compra_subtotal_unitario" class="form-control" value="{{old('precio_compra_subtotal_unitario')}}">
  </div>
  <div class="col-lg-2">
              <label for="ivab" class="control-label">% IVA</label>
              <select name="ivab" id="ivab" class="form-control">
                <option value="">Seleccione</option>
                <option value="0">0%</option>
                <option value="5">5%</option>
                <option value="19">19%</option>
              </select>
              <small class="text-muted">% de Iva</small>
   </div>
<div class="col-lg-2">
    <label for="iva" class="col-xs-4 control-label ">Valor Iva Unitario</label>
    <input type="text" name="iva" id="iva" class="form-control " aria-describedby="iva"
        readonly>
    <small id="iva1" class="text-muted">Valor iva</small>
</div>
 
</div>
<div class="form-group row">
    
  <div class="col-lg-3">
    <label for="cantidad_iva_total" class="col-xs-4 control- requerido ">Iva total</label>
    <input type="text" name="cantidad_iva_total" id="cantidad_iva_total" class="form-control " value="{{old('cantidad_iva_total')}}" readonly>
  </div>
  <div class="col-lg-4">
    <label for="precio_compra_subtotal" class="col-xs-4 control-label ">Sub-Total</label>
    <input type="text" name="precio_compra_subtotal" id="precio_compra_subtotal" class="form-control " placeholder="$0.00"readonly>
  </div>
  <div class="col-lg-4">
    <label for="precio_compra_total" class="col-xs-4 control-label  ">Total</label>
    <input type="text" name="precio_compra_total" id="precio_compra_total" class="form-control " placeholder="$0.00"  readonly>
  </div>
</div>

<div class="form-group row">
  <div class="col-lg-6">
    <label for="usuario_id" class="col-xs-4 control-label ">Usuario</label>
    <input name="usuario_id" id="usuario_id" class="form-control" value="{{ Auth::user()->name ?? '' }}" readonly>
  </div>

  <div class="col-lg-6">
    <label for="created_at" class="col-xs-4 control-label ">Fecha</label>
    <input name="created_at" id="created_at" class="form-control" value="{{now() ?? ''}}" readonly>
  </div>
</div>

<div class="col-lg-3">
  <input type="hidden" name="usuario_id" id="usuario_id" class="form-control" value="{{ Auth::user()->name ?? '' }}" readonly>
</div>
