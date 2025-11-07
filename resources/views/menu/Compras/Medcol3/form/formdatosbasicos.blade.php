<div class="form-group row">
            <div class="col-lg-2">
                <label for="documento" class="col-xs-4 control-label requerido"><i class="fas fa-date"> Documento </i></label>
                <select name="documento" id="documento" class="form-control select2bs4" style="width: 100%;" required>
                </select>
            </div>
            <div class="col-lg-2">
                <label for="consecutivo" class="col-xs-4 control-label requerido"><i class="fas fa-date"> Cons. documento</i></label>
                <input type="number" name="consecutivo" id="consecutivo" class="form-control" value="{{ old('consecutivo') }}" required readonly>
            </div>
             <div class="col-lg-3">
                <label for="proveedor_id" class="col-xs-4 control-label requerido"><i class="fas fa-shipping-fast"> Proveedor</i></label>
                <select name="proveedor_id" id="proveedor_id" class="form-control select2bs4" style="width: 100%;" required>
                </select>
            </div>
            <div class="col-lg-2">
                <label for="codigop" class="col-xs-4 control-label requerido"><i class="fas fa-home"> Código Proveedor</i></label>

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
            <div class="col-lg-2">
                <label for="fecha_facturae" class="col-xs-4 control-label requerido"><i class="fas fa-date"> Fecha Orden</i></label>
                <input type="date" name="fecha_facturae" id="fecha_facturae" class="form-control" value="{{ old('fecha_facturae') }}"
                    required>
            </div>

            <div class="col-lg-2">
                <label for="farmacia" class="col-xs-4 control-label requerido"><i class="fas fa-date">Centro de Producción</i></label>
                <select name="farmacia" id="farmacia" class="form-control form-control-sm select2bs4" style="width: 100%;" required>
                                    <option value="">Seleccione opción...</option>
                                    <option value="BIO1">BIO1-FARMACIA BIOLOGICOS</option>
                                    <option value="DLR1">DLR1-FARMACIA DOLOR</option> 
                                    <option value="DPA1">DPA1-FARMACIA PALIATIVOS</option>
                                    <option value="EHU1">EHU1-FARMACIA HUERFANAS</option>
                                    <option value="EM01">EM01-FARMACIA EMCALI</option>
                                    <option value="EVEN">EVEN-FARMACIA EVENTO</option>
                                    <option value="COOS">COOS-FARMACIA COOSALUD</option>                                   
                                    <option value="COMF">COMF-FARMACIA COMFENALCO</option>
                                    <option value="FRJA">FRJA-FARMACIA JAMUNDI</option>
                                    <option value="INY">INY-FARMACIA INYECTABLES</option>
                                    <option value="PAC">PAC-FARMACIA PAC</option>
                                    <option value="SM01">SM01-FARMACIA SALUD MENTAL</option>
                                    <option value="FRIO">FRIO-FARMACIA IDEO</option>
                                    <option value="SAPRO">SAPRO-SALA DE PROCEDIMIENTOS</option>
                                    <option value="CENAC">CENAC-CENTRO DE ACOPIO</option>
                                    <option value="EVSM">EVSM-EVENTO SALUD MENTAL</option>
                                    <option value="EVIO">EVIO-EVENTO IDEO</option>
                                    <option value="BDNT">BOLSA NORTE</option>                                   
                                    <option value="BPDT">BPDT-BOLSA</option>
                                    <option value="FPEND">FPEND-FARMACIA PENDIENTES</option>
                                </select>
            </div>

            <div class="col-lg-2">
                <label for="numeroOrden" class="col-xs-4 control-label">Dirigida a</i></label>

                <select name="numeroOrden" id="numeroOrden" class="form-control form-control-sm select2bs4" style="width: 100%;" required>
                                    <option value="">Seleccione opción...</option>
                                    <option value="V">VITALIA</option>
                                    <option value="M">MEDCOL</option>                                    
                                </select>
                
            </div>

            <div class="col-lg-2">
                <label for="ClasiOrden" class="col-xs-4 control-label">Clasificación de la Orden</i></label>

                <select name="ClasiOrden" id="ClasiOrden" class="form-control form-control-sm select2bs4" style="width: 100%;" required>
                                    <option value="">Seleccione opción...</option>
                                    <option value="PG">Pedido General</option>
                                    <option value="Queja">Queja</option>   
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Contingencia">Contingencia</option>                                   
                                </select>
                
            </div>
            <div class="col-lg-8">
                <label for="descripcion1" class="col-xs-4 control-label requerido"><i class="fas fa fa-file"> Observaciones</i></label>
                <textarea type="text" name="descripcion1" id="descripcion1" class="form-control" > </textarea>
            </div>
            
        </div>
        <input type="hidden" name="user_id" id="user_ids" for="user_ids" class="form-control" value="{{ Auth::user()->id ?? '' }}" >
        
        @include('menu.Compras.Medcol3.tablas.tablaIndexDetalleEntrada')

        <div class="form-group row">
            <div class="col-lg-6">
               
                <input type="hidden" name="totalSubtotalhidden" id="totalSubtotalhidden" class="form-control validanumericos" value="{{ old('totalSubtotalhidden') }}"
                readonly>
            </div>
           
            <div class="col-lg-2">
                <label for="totalSubtotal" class="col-xs-4 control-label"><i class="fas fa-comment-dollar"><strong> Total Subtotal:</strong></i></label>
                <input type="text" name="totalSubtotal" id="totalSubtotal" class="form-control " value="{{ old('totalSubtotal') }}"
                readonly>
            </div>
            <div class="col-lg-2">
                <label for="totalIva" class="col-xs-4 control-label requerido"><i class="fas fa-comment-dollar"><strong> Total IVA:</strong></i></label>
                <input type="text" name="totalIva" id="totalIva" class="form-control " value="{{ old('totalIva') }}"
                readonly>
            </div>
            <div class="col-lg-2">
                <label for="totalTotal" class="col-xs-4 control-label requerido"><i class="fas fa-comment-dollar"><strong> Total Total:</strong> </i></label>
                <input type="text" name="totalTotal" id="totalTotal" class="form-control " value="{{ old('totalTotal') }}"
               readonly>
            </div>
            
        </div>
