<!-- Formulario de Gestión de Documentos Pendientes -->
<div id="alert-container"></div>
<form id="documentManagementForm" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="action" id="action" value="Edit">
    <input type="hidden" name="hidden_id" id="hidden_id">
    <!-- Información del Paciente -->
    <div class="modal-section">
        <div class="modal-section-title">
            <i class="fas fa-user"></i>
            Información del Paciente
        </div>
        <div class="modal-form-grid">
            <div class="modal-form-group half-width">
                <label for="nombre_completo">Paciente</label>
                <input type="text" id="nombre_completo" class="form-control" readonly>
            </div>
            <div class="modal-form-group">
                <label for="Tipodocum">Identificación</label>
                <input type="text" name="Tipodocum" id="Tipodocum" class="form-control" readonly>
            </div>
            <div class="modal-form-group">
                <label for="historia">Historia Clínica</label>
                <input type="text" name="historia" id="historia" class="form-control" minlength="5" readonly>
            </div>
            <div class="modal-form-group">
                <label for="cantedad">Edad</label>
                <input type="text" name="cantedad" id="cantedad" class="form-control" readonly>
            </div>
        </div>
    </div>

    <!-- Información de Contacto -->
    <div class="modal-section">
        <div class="modal-section-title">
            <i class="fas fa-address-card"></i>
            Información de Contacto
        </div>
        <div class="modal-form-grid">
            <div class="modal-form-group half-width">
                <label for="direcres" class="required">Dirección</label>
                <input type="text" name="direcres" id="direcres" class="form-control" minlength="6" readonly>
            </div>
            <div class="modal-form-group">
                <label for="telefres" class="required">Teléfono</label>
                <input type="text" name="telefres" id="telefres" class="form-control" readonly>
            </div>
            <div class="modal-form-group">
                <label for="documento" class="required">Comprobante</label>
                <input type="text" name="documento" id="documento" class="form-control" minlength="6" readonly>
            </div>
            <div class="modal-form-group">
                <label for="factura" class="required">Pendiente No.</label>
                <input type="text" name="factura" id="factura" class="form-control" readonly>
            </div>
        </div>
    </div>

    <!-- Información del Medicamento -->
    <div class="modal-section">
        <div class="modal-section-title">
            <i class="fas fa-pills"></i>
            Información del Medicamento
        </div>
        <div class="modal-form-grid">
            <div class="modal-form-group">
                <label for="fecha_factura" class="required">Fecha Pendiente</label>
                <input type="date" name="fecha_factura" id="fecha_factura" class="form-control" readonly>
            </div>
            <div class="modal-form-group">
                <label for="cajero" class="required">Auxiliar Dispensó</label>
                <input type="text" name="cajero" id="cajero" class="form-control" readonly>
            </div>
            <div class="modal-form-group">
                <label for="codigo" class="required">Código</label>
                <input type="text" name="codigo" id="codigo" class="form-control" readonly>
            </div>
            <div class="modal-form-group">
                <label for="cums" class="required">CUMS</label>
                <input type="text" name="cums" id="cums" class="form-control" readonly>
            </div>
            <div class="modal-form-group">
                <label for="centroproduccion" class="required">Servicio</label>
                <input type="text" name="centroproduccion" id="centroproduccion" class="form-control" readonly>
            </div>
            <div class="modal-form-group half-width">
                <label for="nombre" class="required">Medicamento / Insumo</label>
                <input type="text" name="nombre" id="nombre" class="form-control" readonly>
            </div>
            <div class="modal-form-group half-width">
                <label for="observ" class="required">Observaciones MP</label>
                <input type="text" name="observ" id="observ" class="form-control" readonly>
            </div>
        </div>
    </div>

    <!-- Gestión del Documento -->
    <div class="modal-section modal-management-section">
        <div class="modal-section-title">
            <i class="fas fa-cogs"></i>
            Gestionar el Documento Pendiente
        </div>
        <div class="modal-form-grid">
            <div class="modal-form-group">
                <label for="cantord" class="required">Cantidad Ordenada</label>
                <input type="number" name="cantord" id="cantord" class="form-control" min="1" step="1" required>
            </div>
            <div class="modal-form-group">
                <label for="cantdpx" class="required">Cantidad Entregada</label>
                <input type="number" name="cantdpx" id="cantdpx" class="form-control" min="1" step="1" required>
            </div>
            <div class="modal-form-group">
                <label for="cant_pndt" class="required">Cantidad Pendiente</label>
                <input type="number" name="cant_pndt" id="cant_pndt" class="form-control" readonly tabindex="-1">
                <span class="modal-quantity-badge">Auto-calculado</span>
            </div>
            <div class="modal-form-group">
                <label for="saldo_medicamento">💊 Saldo Disponible</label>
                <input type="number" name="saldo_medicamento" id="saldo_medicamento" class="form-control" readonly tabindex="-1">
                <span class="modal-quantity-badge saldo-badge">Inventario actual</span>
            </div>
            <div class="modal-form-group">
                <label for="estado" class="col-xs-4 control-label requerido">Estado</label>
                <select name="estado" id="estado" class="form-control select2bs4" style="width: 100%;" required>
                    <option value="">---Seleccione Estado---</option>
                    <option value="PENDIENTE">📋 PENDIENTE</option>
                    <option value="ENTREGADO">✅ ENTREGADO</option>
                    <option value="DESABASTECIDO">❌ DESABASTECIDO</option>
                    <option value="ANULADO">🚫 ANULADO</option>
                </select>
            </div>
        </div>

        <!-- Fechas Dinámicas -->
        <div class="modal-status-grid">
            <div id="futuro1" class="modal-status-item hidden">
                <label for="fecha_entrega">📅 Fecha Entrega</label>
                <input type="date" name="fecha_entrega" id="fecha_entrega" class="form-control">
                <input type="hidden" name="enviar_fecha_entrega" id="enviar_fecha_entrega" value="false">
                
                <!-- <label for="doc_entrega_inline">📄 Doc Entrega</label>
                <input type="text" name="doc_entrega_inline" id="doc_entrega_inline" class="form-control" placeholder="Ej: CDDO">
                
                <label for="factura_entrega_inline">🧾 Factura Entrega</label>
                <input type="text" name="factura_entrega_inline" id="factura_entrega_inline" class="form-control" placeholder="No. Factura"> -->
                
                <input type="hidden" name="enviar_factura_entrega" id="enviar_factura_entrega" value="false">
            </div>
            <div id="futuro2" class="modal-status-item hidden">
                <label for="fecha_impresion">📋 Fecha Tramitado</label>
                <input type="date" name="fecha_impresion" id="fecha_impresion" class="form-control">
                <input type="hidden" name="enviar_fecha_impresion" id="enviar_fecha_impresion" value="false">
            </div>
            <div id="futuro3" class="modal-status-item hidden">
                <label for="fecha">📅 Fecha Pendiente</label>
                <input type="date" name="fecha" id="fecha" class="form-control" readonly>
            </div>
            <div id="futuro4" class="modal-status-item hidden">
                <label for="fecha_anulado">❌ Fecha Anulación</label>
                <input type="date" name="fecha_anulado" id="fecha_anulado" class="form-control">
                <input type="hidden" name="enviar_fecha_anulado" id="enviar_fecha_anulado" value="false">
            </div>
        </div>
    </div>

    <!-- Información de Entrega y Observaciones -->
    <div class="modal-section modal-user-info">
        <div class="modal-section-title">
            <i class="fas fa-user-edit"></i>
            Información de Registro
        </div>
        <div class="modal-form-grid">
            <div class="modal-form-group">
                <label for="name">Usuario que Registró</label>
                <input name="name" id="name" class="form-control" value="{{ Auth::user()->name ?? '' }}" readonly>
            </div>
            <div class="modal-form-group">
                <label for="doc_entrega" class="required">Doc Entrega</label>
                <input type="text" name="doc_entrega" id="doc_entrega" class="form-control" minlength="6" readonly>
            </div>
            <div class="modal-form-group">
                <label for="factura_entrega" class="required">Factura Entrega</label>
                <input type="number" name="factura_entrega" id="factura_entrega" class="form-control" placeholder="No. Factura Rfast...">
                <input type="hidden" name="enviar_factura_entrega" id="enviar_factura_entrega" value="false">
            </div>
            <div class="modal-form-group full-width">
                <label for="observacion" class="required">Observaciones</label>
                <textarea name="observacion" id="observacion" class="form-control UpperCase" rows="4" placeholder="Ingrese las observaciones..." required></textarea>
            </div>
        </div>
    </div>
    
    <!-- Botones de Acción -->
    <div class="modal-actions">
        <button type="submit" class="btn btn-success btn-lg" id="guardar_pendiente">
            <i class="fas fa-save"></i> Guardar Cambios
        </button>
        <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal">
            <i class="fas fa-times"></i> Cancelar
        </button>
        <button type="reset" class="btn btn-warning btn-lg" id="limpiar_form">
            <i class="fas fa-broom"></i> Limpiar
        </button>
    </div>
</form>
