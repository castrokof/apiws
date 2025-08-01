<!-- Formulario de Gestión de Documentos Pendientes -->
<style>
    #alert-container {
        position: sticky;
        top: 0;
        z-index: 1050;
        margin-bottom: 15px;
    }
    
    .alert {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-left: 4px solid #28a745;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border-left: 4px solid #dc3545;
    }
    
    .alert-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border-left: 4px solid #ffc107;
    }
    
    .alert-info {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        border-left: 4px solid #17a2b8;
    }
    
    .btn-close {
        background: none;
        border: none;
        font-size: 1.2em;
        cursor: pointer;
        opacity: 0.7;
    }
    
    .btn-close:hover {
        opacity: 1;
    }
    
    .modal-status-item.hidden {
        display: none !important;
    }
    
    .form-control[required] {
        border-left: 3px solid #dc3545;
    }
    
    .form-control[required]:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
</style>

<div id="alert-container" class="mb-3"></div>
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
        claseCSS = 'form-control text-info font-weight-bold';
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
    setInterval(calcularMetricasEntrega, 6000); // Actualizar cada minuto
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

// ===== SISTEMA DE VALIDACIONES DINÁMICAS Y ALERTAS =====

// Función para mostrar alertas mejoradas
function mostrarAlerta(tipo, titulo, mensaje, duracion = 5000) {
    const alertContainer = document.getElementById('alert-container');
    if (!alertContainer) {
        console.error('Contenedor de alertas no encontrado');
        return;
    }

    const alertId = 'alert-' + Date.now();
    const iconos = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-triangle',
        'warning': 'fas fa-exclamation-circle',
        'info': 'fas fa-info-circle'
    };

    const colores = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    };

    const alertHTML = `
        <div id="${alertId}" class="alert ${colores[tipo]} alert-dismissible fade show shadow-sm" role="alert" style="margin-bottom: 15px;">
            <div class="d-flex align-items-center">
                <i class="${iconos[tipo]} me-2" style="font-size: 1.2em;"></i>
                <div class="flex-grow-1">
                    <strong>${titulo}</strong>
                    <div style="white-space: pre-line; margin-top: 5px;">${mensaje}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" onclick="cerrarAlerta('${alertId}')" style="margin-left: 10px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;

    alertContainer.innerHTML = alertHTML + alertContainer.innerHTML;
    
    // Auto-ocultar después del tiempo especificado
    if (duracion > 0) {
        setTimeout(() => cerrarAlerta(alertId), duracion);
    }
    
    // Scroll hacia la alerta
    alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function cerrarAlerta(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.classList.remove('show');
        setTimeout(() => alert.remove(), 300);
    }
}

// Función para validar campos según el estado seleccionado
function actualizarValidacionesPorEstado() {
    const estadoSelect = document.getElementById('estado');
    const cantdpxInput = document.getElementById('cantdpx');
    const facturaEntregaInput = document.getElementById('factura_entrega');
    const observacionTextarea = document.getElementById('observacion');
    
    if (!estadoSelect) return;
    
    const estado = estadoSelect.value;
    
    // Resetear validaciones
    cantdpxInput.removeAttribute('required');
    facturaEntregaInput.removeAttribute('required');
    observacionTextarea.removeAttribute('required');
    
    // Aplicar validaciones según el estado
    switch (estado) {
        case 'ENTREGADO':
            cantdpxInput.setAttribute('required', 'required');
            cantdpxInput.setAttribute('min', '1');
            facturaEntregaInput.setAttribute('required', 'required');
            observacionTextarea.setAttribute('required', 'required');
            
            // Mostrar campos obligatorios
            mostrarCamposSegunEstado('ENTREGADO');
            break;
            
        case 'DESABASTECIDO':
            cantdpxInput.removeAttribute('required');
            cantdpxInput.setAttribute('min', '0');
            facturaEntregaInput.removeAttribute('required');
            observacionTextarea.removeAttribute('required');
            
            mostrarCamposSegunEstado('DESABASTECIDO');
            break;
            
        case 'ANULADO':
            cantdpxInput.removeAttribute('required');
            cantdpxInput.setAttribute('min', '0');
            facturaEntregaInput.removeAttribute('required');
            observacionTextarea.removeAttribute('required');
            
            mostrarCamposSegunEstado('ANULADO');
            break;
            
        case 'PENDIENTE':
        default:
            cantdpxInput.removeAttribute('required');
            cantdpxInput.setAttribute('min', '0');
            facturaEntregaInput.removeAttribute('required');
            observacionTextarea.removeAttribute('required');
            
            mostrarCamposSegunEstado('PENDIENTE');
            break;
    }
    
    // Actualizar estilos visuales
    actualizarEstilosValidacion();
}

function mostrarCamposSegunEstado(estado) {
    // Ocultar todos los campos de fecha específicos
    document.getElementById('futuro1').style.display = 'none'; // fecha_entrega
    document.getElementById('futuro2').style.display = 'none'; // fecha_impresion
    document.getElementById('futuro3').style.display = 'none'; // fecha (pendiente)
    document.getElementById('futuro4').style.display = 'none'; // fecha_anulado
    
    // Mostrar campos según el estado
    switch (estado) {
        case 'ENTREGADO':
            document.getElementById('futuro1').style.display = 'block';
            break;
        case 'DESABASTECIDO':
            document.getElementById('futuro2').style.display = 'block';
            break;
        case 'ANULADO':
            document.getElementById('futuro4').style.display = 'block';
            break;
        case 'PENDIENTE':
            document.getElementById('futuro3').style.display = 'block';
            break;
    }
}

function actualizarEstilosValidacion() {
    const inputs = document.querySelectorAll('#documentManagementForm input[required], #documentManagementForm textarea[required]');
    inputs.forEach(input => {
        input.style.borderColor = '#dc3545';
        input.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
    });
}

// Manejo del envío del formulario con alertas mejoradas
function configurarEnvioFormulario() {
    const form = document.getElementById('documentManagementForm');
    const guardarBtn = document.getElementById('guardar_pendiente');
    
    if (!form || !guardarBtn) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const pendienteId = document.getElementById('hidden_id')?.value;
        
        if (!pendienteId) {
            mostrarAlerta('error', 'Error', 'ID del pendiente no encontrado', 5000);
            return;
        }
        
        // Construir URL correcta para la actualización
        const baseUrl = window.location.origin;
        const updateUrl = `${baseUrl}/pendientes-medcol6/${pendienteId}`;
        
        // Mostrar loading
        guardarBtn.disabled = true;
        guardarBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        
        // Agregar método PUT al FormData
        formData.append('_method', 'PUT');
        
        fetch(updateUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            console.log('Respuesta del servidor:', data); // Debug
            
            if (response.ok && data.success) {
                mostrarAlerta('success', '✅ Éxito', data.message || 'Pendiente actualizado correctamente', 2000);
                
                // Limpiar y resetear formulario
                limpiarFormulario();
                
                // Cerrar modal y recargar tabla inmediatamente
                setTimeout(() => {
                    cerrarModalYRecargar();
                }, 1500);
                
            } else {
                // Manejar errores de validación o del servidor
                if (data.formatted_message) {
                    mostrarAlerta('error', 'Errores de Validación', data.formatted_message, 8000);
                } else if (data.errors && data.errors.length > 0) {
                    const erroresTexto = data.errors.join('\n• ');
                    mostrarAlerta('error', 'Errores de Validación', '• ' + erroresTexto, 8000);
                } else {
                    mostrarAlerta('error', 'Error', data.message || 'Error al actualizar el pendiente', 5000);
                }
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
            mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor. Intente nuevamente.', 5000);
        })
        .finally(() => {
            // Restaurar botón
            guardarBtn.disabled = false;
            guardarBtn.innerHTML = '<i class="fas fa-save"></i> Guardar Cambios';
        });
    });
}

function cerrarModalYRecargar() {
    try {
        console.log('Iniciando cierre de modal y recarga...');
        
        // Método 1: Intentar cerrar usando Bootstrap 5
        const modalElement = document.querySelector('.modal.show');
        if (modalElement) {
            console.log('Modal encontrado, cerrando...');
            
            // Intentar con Bootstrap 5
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) {
                    modalInstance.hide();
                    console.log('Modal cerrado con Bootstrap 5');
                } else {
                    // Crear nueva instancia si no existe
                    const newModal = new bootstrap.Modal(modalElement);
                    newModal.hide();
                    console.log('Modal cerrado con nueva instancia Bootstrap 5');
                }
            }
            // Intentar con Bootstrap 4 (fallback)
            else if (typeof $ !== 'undefined' && $.fn.modal) {
                $(modalElement).modal('hide');
                console.log('Modal cerrado con Bootstrap 4/jQuery');
            }
            // Método manual
            else {
                modalElement.style.display = 'none';
                modalElement.classList.remove('show');
                document.body.classList.remove('modal-open');
                
                // Remover backdrop si existe
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                console.log('Modal cerrado manualmente');
            }
        }
        
        // Método 2: Intentar cerrar por ID específico del modal
        const specificModal = document.getElementById('modalEditarPendiente') || 
                            document.getElementById('modal-edit-pendiente') ||
                            document.querySelector('[id*="modal"][id*="pendiente"]');
        
        if (specificModal && specificModal !== modalElement) {
            console.log('Modal específico encontrado, cerrando...');
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modalInstance = bootstrap.Modal.getInstance(specificModal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                $(specificModal).modal('hide');
            }
        }
        
        // Método 3: Recargar tabla
        setTimeout(() => {
            console.log('Intentando recargar tabla...');
            
            // Buscar diferentes nombres de funciones de recarga
            const recargarFunciones = [
                'recargarTablaPendientes',
                'recargarTabla', 
                'refreshTable',
                'loadPendientes',
                'actualizarTabla'
            ];
            
            let funcionEncontrada = false;
            for (const nombreFuncion of recargarFunciones) {
                if (typeof window[nombreFuncion] === 'function') {
                    console.log(`Ejecutando función: ${nombreFuncion}`);
                    window[nombreFuncion]();
                    funcionEncontrada = true;
                    break;
                }
            }
            
            // Si hay DataTable, intentar recargar
            if (!funcionEncontrada && typeof $ !== 'undefined' && $.fn.DataTable) {
                const table = $('.dataTable').DataTable();
                if (table) {
                    console.log('Recargando DataTable...');
                    table.ajax.reload(null, false);
                    funcionEncontrada = true;
                }
            }
            
            // Último recurso: recargar página
            if (!funcionEncontrada) {
                console.log('Recargando página completa...');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        }, 500);
        
    } catch (error) {
        console.error('Error al cerrar modal:', error);
        // Fallback: recargar página
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }
}

// Función para limpiar formulario después del éxito
function limpiarFormulario() {
    try {
        const form = document.getElementById('documentManagementForm');
        if (form) {
            console.log('Limpiando formulario...');
            
            // Resetear campos no readonly
            const inputs = form.querySelectorAll('input:not([readonly]), textarea:not([readonly]), select');
            inputs.forEach(input => {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                } else {
                    input.value = '';
                }
            });
            
            // Limpiar campos específicos de métricas
            document.getElementById('dias_transcurridos').value = '0';
            document.getElementById('fecha_estimada_entrega').value = '';
            document.getElementById('horas_restantes').value = 'N/A';
            document.getElementById('estado_prioridad').value = 'SIN FECHA';
            
            // Ocultar campos de fecha específicos
            document.getElementById('futuro1').style.display = 'none';
            document.getElementById('futuro2').style.display = 'none';
            document.getElementById('futuro3').style.display = 'none';
            document.getElementById('futuro4').style.display = 'none';
            
            console.log('Formulario limpiado correctamente');
        }
    } catch (error) {
        console.error('Error al limpiar formulario:', error);
    }
}

// Función de debug para el estado del modal
function debugModal() {
    console.log('=== DEBUG MODAL ===');
    console.log('Modales encontrados:', document.querySelectorAll('.modal').length);
    console.log('Modales con clase show:', document.querySelectorAll('.modal.show').length);
    console.log('Bootstrap disponible:', typeof bootstrap !== 'undefined');
    console.log('jQuery disponible:', typeof $ !== 'undefined');
    console.log('DataTable disponible:', typeof $.fn?.DataTable !== 'undefined');
    
    const modal = document.querySelector('.modal.show');
    if (modal) {
        console.log('Modal activo ID:', modal.id || 'sin ID');
        console.log('Modal activo clases:', modal.className);
    }
    console.log('==================');
}

// Función global para debug (puede ser llamada desde consola)
window.debugModal = debugModal;

// Inicializar todas las funcionalidades al cargar
document.addEventListener('DOMContentLoaded', function() {
    iniciarActualizacionAutomatica();
    
    const fechaFacturaElement = document.getElementById('fecha_factura');
    if (fechaFacturaElement) {
        fechaFacturaElement.addEventListener('change', calcularMetricasEntrega);
    }
    
    // Configurar validaciones dinámicas
    const estadoSelect = document.getElementById('estado');
    if (estadoSelect) {
        estadoSelect.addEventListener('change', actualizarValidacionesPorEstado);
        // Ejecutar una vez al cargar
        actualizarValidacionesPorEstado();
    }
    
    // Configurar envío de formulario
    configurarEnvioFormulario();
    
    // También calcular cuando se abra/cargue el modal
    if (typeof window.modalOpened !== 'undefined') {
        calcularMetricasEntrega();
    }
});
</script>