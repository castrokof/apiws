

        <div class="form-group row">
            <div class="col-lg-2">
                <label for="documento" class="col-xs-4 control-label requerido"><i class="fas fa-date"> Documento </i></label>
                <select name="documento" id="documento" class="form-control select2bs4" style="width: 100%;" required>
                </select>
            </div>
            <div class="col-lg-2">
                <label for="consecutivo" class="col-xs-4 control-label requerido"><i class="fas fa-date"> Cons. documento</i></label>
                <input type="number" name="consecutivo" id="consecutivo" class="form-control" value="{{ old('nombrep') }}" required readonly>
            </div>
             <div class="col-lg-3">
                <label for="proveedor_id" class="col-xs-4 control-label requerido"><i class="fas fa-shipping-fast"> Proveedor</i></label>
                <select name="proveedor_id" id="proveedor_id" class="form-control select2bs4" style="width: 100%;" required>
                </select>
            </div>
            <div class="col-lg-2">
                <label for="codigop" class="col-xs-4 control-label requerido"><i class="fas fa-home"> Codigo Proveedor</i></label>

                <input type="text" name="codigop" id="codigop" class="form-control" value="{{ old('codigop') }}" readonly required>
                
            </div>
            <div class="col-lg-2">
                <label for="nombrep" class="col-xs-4 control-label requerido"><i class="far fa-address-card"> Nombre Proveedor</i></label>
                <input type="text" name="nombrep" id="nombrep" class="form-control" value="{{ old('nombrep') }}" readonly required>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-lg-2">
                <label for="contrato" class="col-xs-4 control-label requerido"><i class="fas fa-date"> Contrato</i></label>
                <select name="contrato" id="contrato" class="form-control select2bs4" style="width: 100%;" required>
                </select>
            </div>
            <!-- <div class="col-lg-2">
                <label for="codigo" class="col-xs-4 control-label requerido"><i class="fas fa-date"> Cons. Factura</i></label>
                <input type="number" name="codigof" id="codigof" class="form-control" value="{{ old('nombrep') }}" required>
            </div> -->
            <div class="col-lg-2">
                <label for="fecha_facturae" class="col-xs-4 control-label requerido"><i class="fas fa-date"> Fecha Orden</i></label>
                <input type="date" name="fecha_facturae" id="fecha_facturae" class="form-control" value="{{ old('fecha_facturae') }}"
                    required>
            </div>
            
            <div class="col-lg-2">
                <label for="precio_compra_subtotal" class="col-xs-4 control-label requerido"><i class="fas fa-comment-dollar"> Sub total</i></label>
                <input type="text" name="precio_compra_subtotalf" id="precio_compra_subtotalf" class="form-control validanumericos" value="{{ old('precio_compra_subtotal') }}"
                required>
            </div>
            <div class="col-lg-2">
                <label for="totaliva" class="col-xs-4 control-label requerido"><i class="fas fa-comment-dollar"> Valor Iva</i></label>
                <input type="text" name="totaliva" id="totaliva" class="form-control validanumericos" value="{{ old('totaliva') }}"
                required>
            </div>
            <div class="col-lg-2">
                <label for="precio_compra_total" class="col-xs-4 control-label requerido"><i class="fas fa-comment-dollar"> Total</i></label>
                <input type="text" name="precio_compra_totalf" id="precio_compra_totalf" class="form-control validanumericos" value="{{ old('precio_compra_total') }}"
               readonly required>
            </div>
            
        </div>
        <input type="hidden" name="user_id" id="user_ids" class="form-control" value="{{Session()->get('usuario_id')}}" >
        
        @include('menu.Compras.Medcol2.tablas.tablaIndexDetalleEntrada')

        <div class="form-group row">
            <div class="col-lg-6">
               
                <input type="hidden" name="totalSubtotalhidden" id="totalSubtotalhidden" class="form-control validanumericos" value="{{ old('totalSubtotalhidden') }}"
                readonly>
            </div>
           
            <div class="col-lg-2">
                <label for="totalSubtotal" class="col-xs-4 control-label"><i class="fas fa-comment-dollar"><strong>Total Subtotal:</strong></i></label>
                <input type="text" name="totalSubtotal" id="totalSubtotal" class="form-control " value="{{ old('totalSubtotal') }}"
                readonly>
            </div>
            <div class="col-lg-2">
                <label for="totalIva" class="col-xs-4 control-label requerido"><i class="fas fa-comment-dollar"><strong>Total IVA:</strong></i></label>
                <input type="text" name="totalIva" id="totalIva" class="form-control " value="{{ old('totalIva') }}"
                readonly>
            </div>
            <div class="col-lg-2">
                <label for="totalTotal" class="col-xs-4 control-label requerido"><i class="fas fa-comment-dollar"><strong>Total Total:</strong> </i></label>
                <input type="text" name="totalTotal" id="totalTotal" class="form-control " value="{{ old('totalTotal') }}"
               readonly>
            </div>
            
        </div>

       
        