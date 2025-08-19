<fieldset>
            <legend>Información del Paciente</legend>
            
            <div class="form-group row">
                <div class="col-lg-3">
                    <label for="nombre1_n" class="control-label requerido">Primer nombre</label>
                    <input type="text" name="nombre1_n" id="nombre1_n" class="form-control" readonly required>
                </div>
                <div class="col-lg-3">
                    <label for="nombre2_n" class="control-label">Segundo nombre</label>
                    <input type="text" name="nombre2_n" id="nombre2_n" class="form-control" readonly>
                </div>
                <div class="col-lg-3">
                    <label for="apellido1_n" class="control-label requerido">Primer apellido</label>
                    <input type="text" name="apellido1_n" id="apellido1_n" class="form-control" readonly required>
                </div>
                <div class="col-lg-3">
                    <label for="apellido2_n" class="control-label">Segundo apellido</label>
                    <input type="text" name="apellido2_n" id="apellido2_n" class="form-control" readonly>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-3">
                    <label for="Tipodocum_n" class="control-label">Tipo de identificación</label>
                    <input type="text" name="Tipodocum_n" id="Tipodocum_n" class="form-control" readonly>
                </div>
                <div class="col-lg-3">
                    <label for="historia_n" class="control-label requerido">No. de identificación</label>
                    <input type="text" name="historia_n" id="historia_n" class="form-control" minlength="5" readonly required>
                </div>
                <div class="col-lg-2">
                    <label for="cantedad_n" class="control-label">Edad</label>
                    <input type="text" name="cantedad_n" id="cantedad_n" class="form-control" readonly>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-6">
                    <label for="direcres_n" class="control-label requerido">Dirección</label>
                    <input type="text" name="direcres_n" id="direcres_n" class="form-control" minlength="6" readonly required>
                </div>
                <div class="col-lg-3">
                    <label for="telefres_n" class="control-label requerido">Teléfono</label>
                    <input type="tel" name="telefres_n" id="telefres_n" class="form-control" readonly required>
                </div>
            </div>
        </fieldset>

        <!-- Información del Documento -->
        <fieldset>
            <legend>Información del Pendiente</legend>
            
            <div class="form-group row">
                <div class="col-lg-4">
                    <label for="documento_n" class="control-label requerido">Comprobante</label>
                    <input type="text" name="documento_n" id="documento_n" class="form-control" minlength="6" readonly required>
                </div>
                <div class="col-lg-4">
                    <label for="factura_n" class="control-label requerido">Pendiente No.</label>
                    <input type="text" name="factura_n" id="factura_n" class="form-control" readonly required>
                </div>
                <div class="col-lg-4">
                    <label for="fecha_factura_n" class="control-label requerido">Fecha Pendiente</label>
                    <input type="date" name="fecha_factura_n" id="fecha_factura_n" class="form-control" readonly required>
                </div>
            </div>
        </fieldset>

        <!-- Información del Medicamento/Insumo -->
        <fieldset>
            <legend>Información del Medicamento/Insumo</legend>
            
            <div class="form-group row">
                <div class="col-lg-3">
                    <label for="codigo_n" class="control-label requerido">Código</label>
                    <input type="text" name="codigo_n" id="codigo_n" class="form-control" readonly required>
                </div>
                <div class="col-lg-3">
                    <label for="cums_n" class="control-label requerido">CUMS</label>
                    <input type="text" name="cums_n" id="cums_n" class="form-control" readonly required>
                </div>
                <div class="col-lg-6">
                    <label for="centroproduccion_n" class="control-label requerido">Servicio</label>
                    <input type="text" name="centroproduccion_n" id="centroproduccion_n" class="form-control" readonly required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-8">
                    <label for="nombre_n" class="control-label requerido">Medicamento / Insumo</label>
                    <input type="text" name="nombre_n" id="nombre_n" class="form-control" readonly required>
                </div>
                <div class="col-lg-4">
                    <label for="observ_n" class="control-label requerido">Observaciones MP</label>
                    <input type="text" name="observ_n" id="observ_n" class="form-control" readonly required>
                </div>
            </div>
        </fieldset>

        <!-- Detalle del Documento Pendiente -->
        <fieldset>
            <legend style="color: #28a745;">Detalle del Documento Pendiente</legend>
            
            <div class="form-group row">
                <div class="col-lg-3">
                    <label for="cantord_n" class="control-label requerido">Cantidad Ordenada</label>
                    <input type="number" name="cantord_n" id="cantord_n" class="form-control" readonly required>
                </div>
                <div class="col-lg-3">
                    <label for="cantdpx_n" class="control-label requerido">Cantidad Entregada</label>
                    <input type="number" name="cantdpx_n" id="cantdpx_n" class="form-control" readonly required>
                </div>
                <div class="col-lg-3">
                    <label for="cant_pndt_n" class="control-label requerido">Cantidad Pendiente</label>
                    <input type="number" name="cant_pndt_n" id="cant_pndt_n" class="form-control" readonly required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-4">
                    <label for="estado_n" class="control-label requerido">Estado</label>
                    <input name="estado_n" id="estado_n" class="form-control" readonly required>
                </div>
                <div class="col-lg-4">
                    <label for="fecha_estado" class="control-label">Fecha de Estado</label>
                    <input type="date" name="fecha_estado" id="fecha_estado" class="form-control" readonly>
                </div>
                <div class="col-lg-4">
                    <label for="cajero_n" class="control-label requerido">Auxiliar que Dispensó</label>
                    <input type="text" name="cajero_n" id="cajero_n" class="form-control" readonly required>
                </div>
            </div>
        </fieldset>

        <!-- Información Adicional -->
        <fieldset>
            <legend>Información Adicional</legend>
            
            <div class="form-group row">
                <div class="col-lg-6">
                    <label for="usuario_n" class="control-label">Usuario que registró</label>
                    <input name="usuario_n" id="usuario_n" class="form-control" readonly>
                </div>
                <div class="col-lg-6">
                    <label for="fac_entrega" class="control-label requerido">Comprobante Dispensación</label>
                    <input name="fac_entrega" id="fac_entrega" class="form-control" readonly required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-lg-12">
                    <label for="ultima_observacion_n" class="control-label">Observaciones / Novedades</label>
                    <textarea name="ultima_observacion_n" id="ultima_observacion_n" class="form-control" rows="3" readonly></textarea>
                </div>
            </div>
        </fieldset>