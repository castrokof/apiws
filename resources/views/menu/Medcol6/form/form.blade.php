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

        <!-- Nueva sección: Cálculos de Tiempo y Prioridad -->
        <div class="modal-form-grid">
            <div class="modal-form-group">
                <label for="dias_transcurridos">📅 Días Transcurridos</label>
                <input type="number" name="dias_transcurridos" id="dias_transcurridos" class="form-control" readonly tabindex="-1">
                <span class="modal-quantity-badge time-badge">Desde fecha pendiente</span>
            </div>
            <div class="modal-form-group">
                <label for="fecha_estimada_entrega">⏰ Fecha Estimada Entrega</label>
                <input type="datetime-local" name="fecha_estimada_entrega" id="fecha_estimada_entrega" class="form-control" readonly tabindex="-1">
                <span class="modal-quantity-badge delivery-badge">+48 horas</span>
            </div>
            <div class="modal-form-group">
                <label for="horas_restantes">⏳ Tiempo Restante</label>
                <input type="text" name="horas_restantes" id="horas_restantes" class="form-control" readonly tabindex="-1">
                <span class="modal-quantity-badge countdown-badge">Para entrega</span>
            </div>
            <div class="modal-form-group">
                <label for="estado_prioridad">🚨 Estado de Prioridad</label>
                <input type="text" name="estado_prioridad" id="estado_prioridad" class="form-control" readonly tabindex="-1">
                <span class="modal-quantity-badge priority-badge">Basado en 48h</span>
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
                <select name="estado" id="estado" class="form-control" style="width: 100%;" required>
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
            Información de la Entrega y Observaciones
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

<script>
function calcularMetricasEntrega() {
    const fechaFacturaElement = document.getElementById('fecha_factura');
    if (!fechaFacturaElement || !fechaFacturaElement.value) {
        // Limpiar campos si no hay fecha
        document.getElementById('dias_transcurridos').value = 0;
        document.getElementById('fecha_estimada_entrega').value = '';
        document.getElementById('horas_restantes').value = 'N/A';
        document.getElementById('estado_prioridad').value = 'SIN FECHA';
        
        // Aplicar estilos por defecto
        const estadoPrioridadElement = document.getElementById('estado_prioridad');
        estadoPrioridadElement.className = 'form-control text-muted';
        return;
    }

    const fechaFactura = new Date(fechaFacturaElement.value);
    const hoy = new Date();
    
    // Calcular tiempo transcurrido
    const tiempoTranscurrido = hoy - fechaFactura;
    const diasTranscurridos = Math.floor(tiempoTranscurrido / (1000 * 60 * 60 * 24));
    const horasTranscurridas = tiempoTranscurrido / (1000 * 60 * 60);
    
    // Calcular fecha estimada de entrega (48 horas después)
    const fechaEstimada = new Date(fechaFactura);
    fechaEstimada.setHours(fechaEstimada.getHours() + 48);
    
    // Formatear fecha estimada para datetime-local input
    const year = fechaEstimada.getFullYear();
    const month = String(fechaEstimada.getMonth() + 1).padStart(2, '0');
    const day = String(fechaEstimada.getDate()).padStart(2, '0');
    const hours = String(fechaEstimada.getHours()).padStart(2, '0');
    const minutes = String(fechaEstimada.getMinutes()).padStart(2, '0');
    const fechaEstimadaFormatted = `${year}-${month}-${day}T${hours}:${minutes}`;
    
    // Calcular tiempo restante hasta las 48 horas
    const tiempoRestante = (48 * 60 * 60 * 1000) - tiempoTranscurrido;
    const horasRestantes = Math.ceil(tiempoRestante / (1000 * 60 * 60));
    
    let tiempoRestanteTexto;
    if (horasRestantes > 0) {
        if (horasRestantes > 24) {
            const diasRestantes = Math.ceil(horasRestantes / 24);
            tiempoRestanteTexto = `${diasRestantes} día(s)`;
        } else {
            tiempoRestanteTexto = `${horasRestantes} hora(s)`;
        }
    } else {
        const horasExcedidas = Math.abs(horasRestantes);
        if (horasExcedidas > 24) {
            const diasExcedidos = Math.ceil(horasExcedidas / 24);
            tiempoRestanteTexto = `${diasExcedidos} día(s) vencido`;
        } else {
            tiempoRestanteTexto = `${horasExcedidas} hora(s) vencido`;
        }
    }
    
    // Determinar estado y estilos
    let estado, claseCSS;
    
    if (horasTranscurridas <= 24) {
        estado = '🟢 EN TIEMPO';
        claseCSS = 'form-control text-success font-weight-bold';
    } else if (horasTranscurridas <= 48) {
        estado = '🟡 PRIORIDAD';
        claseCSS = 'form-control text-warning font-weight-bold';
    } else if (horasTranscurridas <= 72) {
        estado = '🔴 CRÍTICO';
        claseCSS = 'form-control text-danger font-weight-bold';
    } else {
        estado = '🚨 URGENTE';
        claseCSS = 'form-control text-danger font-weight-bold border-danger';
    }
    
    // Actualizar los campos en el formulario
    document.getElementById('dias_transcurridos').value = diasTranscurridos;
    document.getElementById('fecha_estimada_entrega').value = fechaEstimadaFormatted;
    document.getElementById('horas_restantes').value = tiempoRestanteTexto;
    
    const estadoPrioridadElement = document.getElementById('estado_prioridad');
    estadoPrioridadElement.value = estado;
    estadoPrioridadElement.className = claseCSS;
}

// Función para auto-actualizar cada minuto
function iniciarActualizacionAutomatica() {
    calcularMetricasEntrega();
    setInterval(calcularMetricasEntrega, 60000); // Actualizar cada minuto
}

// Ejecutar al cargar la página y configurar eventos
document.addEventListener('DOMContentLoaded', function() {
    iniciarActualizacionAutomatica();
    
    const fechaFacturaElement = document.getElementById('fecha_factura');
    if (fechaFacturaElement) {
        fechaFacturaElement.addEventListener('change', calcularMetricasEntrega);
    }
    
    // También calcular cuando se abra/cargue el modal
    if (typeof window.modalOpened !== 'undefined') {
        calcularMetricasEntrega();
    }
});

// Función global para recalcular métricas (puede ser llamada desde otros scripts)
window.recalcularMetricasEntrega = calcularMetricasEntrega;
</script>