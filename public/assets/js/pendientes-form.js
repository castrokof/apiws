/**
 * ==============================================
 * GESTIÓN DE FORMULARIO DE PENDIENTES MEDCOL6
 * ==============================================
 */

class PendientesFormManager {
    constructor() {
        this.form = document.getElementById('documentManagementForm');
        this.submitButton = document.getElementById('guardar_pendiente');
        this.resetButton = document.getElementById('limpiar_form');
        this.alertContainer = document.getElementById('alert-container');
        
        this.init();
    }

    init() {
        // Solo inicializar si el formulario existe
        if (this.form) {
            this.bindEvents();
            this.setupFormValidation();
            this.setupQuantityCalculation();
            this.setupStatusHandling();
            console.log('✅ PendientesFormManager inicializado con formulario encontrado');
        } else {
            console.warn('⚠️ Formulario #documentManagementForm no encontrado. Inicialización diferida hasta que el modal se abra.');
            // Configurar inicialización diferida para cuando se abra el modal
            this.setupDeferredInitialization();
        }
    }

    bindEvents() {
        // Evento de submit del formulario
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        }

        // Evento de reset
        if (this.resetButton) {
            this.resetButton.addEventListener('click', () => this.resetForm());
        }

        // NO agregar listener para estado aquí - ya existe en indexAnalista.blade.php
        // En su lugar, exponemos nuestra función como global para que la función original la llame
        window.pendientesFormStatusHandler = (estado) => this.handleStatusChange(estado);

        // Eventos de cálculo de cantidad
        const cantordInput = document.getElementById('cantord');
        const cantdpxInput = document.getElementById('cantdpx');
        
        if (cantordInput) {
            cantordInput.addEventListener('input', () => this.calculatePendingQuantity());
        }
        
        if (cantdpxInput) {
            cantdpxInput.addEventListener('input', () => this.calculatePendingQuantity());
        }

        // Sincronizar campos inline con campos principales
        this.setupFieldSynchronization();
        
        // Configurar el listener del select de estado para llamar a la función original
        const estadoSelect = document.getElementById('estado');
        if (estadoSelect) {
            estadoSelect.addEventListener('change', () => {
                const estado = estadoSelect.value;
                console.log('🔄 Estado cambiado a:', estado);
                
                // Llamar a la función original si existe
                if (typeof mostrarOcultarCampos === 'function') {
                    mostrarOcultarCampos();
                } else {
                    console.warn('⚠️ Función mostrarOcultarCampos no encontrada');
                }
                
                // Llamar a nuestro manejador adicional
                this.handleStatusChange(estado);
                
                // Aplicar estilos visuales al select
                this.applySelectStyles(estado);
            });
        }
    }

    setupDeferredInitialization() {
        // Esperar a que el modal se abra para inicializar
        $(document).on('shown.bs.modal', '#modal-edit-pendientes', () => {
            console.log('🔄 Modal abierto, intentando inicialización diferida...');
            
            // Reintentear obtener el formulario
            this.form = document.getElementById('documentManagementForm');
            this.submitButton = document.getElementById('guardar_pendiente');
            this.resetButton = document.getElementById('limpiar_form');
            this.alertContainer = document.getElementById('alert-container');
            
            if (this.form) {
                console.log('✅ Formulario encontrado, inicializando...');
                this.bindEvents();
                this.setupFormValidation();
                this.setupQuantityCalculation();
                this.setupStatusHandling();
            } else {
                console.error('❌ Formulario aún no encontrado después de abrir el modal');
            }
        });
    }

    setupFormValidation() {
        // Validación con null check
        if (!this.form) {
            console.warn('⚠️ No se puede configurar validación: formulario no encontrado');
            return;
        }
        
        // Validación en tiempo real
        const requiredFields = this.form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => this.clearFieldError(field));
        });
        
        console.log(`✅ Validación configurada para ${requiredFields.length} campos requeridos`);
    }

    setupQuantityCalculation() {
        if (this.form) {
            this.calculatePendingQuantity();
        }
    }

    setupStatusHandling() {
        // No hacer nada aquí, el sistema original se encarga
        // Solo calculamos cantidades
    }

    calculatePendingQuantity() {
        const cantord = parseFloat(document.getElementById('cantord')?.value || 0);
        const cantdpx = parseFloat(document.getElementById('cantdpx')?.value || 0);
        const cantPendiente = Math.max(0, cantord - cantdpx);
        
        const cantPndtInput = document.getElementById('cant_pndt');
        if (cantPndtInput) {
            cantPndtInput.value = cantPendiente;
        }
    }

    handleStatusChange(estado = null) {
        // Si no se pasa estado, obtenerlo del select
        if (!estado) {
            estado = document.getElementById('estado')?.value;
        }
        
        // Usar el sistema existente de jQuery para mostrar/ocultar
        // pero agregar nuestra lógica adicional
        
        // Reset de valores hidden (complementario al sistema existente)
        this.setHiddenValue('enviar_fecha_entrega', 'false');
        this.setHiddenValue('enviar_fecha_impresion', 'false');
        this.setHiddenValue('enviar_fecha_anulado', 'false');
        this.setHiddenValue('enviar_factura_entrega', 'false');

        // Configurar valores según el estado (complementa el sistema jQuery existente)
        switch (estado) {
            case 'ENTREGADO':
                this.setHiddenValue('enviar_fecha_entrega', 'true');
                this.setHiddenValue('enviar_factura_entrega', 'true');
                break;
                
            case 'DESABASTECIDO':
                this.setHiddenValue('enviar_fecha_impresion', 'true');
                break;
                
            case 'ANULADO':
                this.setHiddenValue('enviar_fecha_anulado', 'true');
                break;
        }
    }

    showElement(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            // Usar jQuery para consistencia con el sistema existente
            $(element).show();
            element.classList.remove('hidden');
        }
    }

    setHiddenValue(fieldName, value) {
        const field = document.getElementById(fieldName);
        if (field) {
            field.value = value;
        }
    }

    validateField(field) {
        const value = field.value.trim();
        const isRequired = field.hasAttribute('required');
        const fieldType = field.type;
        const min = parseFloat(field.min);
        
        let isValid = true;
        let errorMessage = '';

        if (isRequired && !value) {
            isValid = false;
            errorMessage = 'Este campo es obligatorio';
        } else if (fieldType === 'number' && value && !isNaN(min) && parseFloat(value) < min) {
            isValid = false;
            errorMessage = `El valor debe ser mayor o igual a ${min}`;
        }

        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            this.showFieldError(field, errorMessage);
        }

        return isValid;
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
    }

    showFieldError(field, message) {
        this.clearFieldError(field);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        
        field.parentNode.appendChild(errorDiv);
    }

    validateForm() {
        if (!this.form) {
            console.warn('⚠️ No se puede validar: formulario no encontrado');
            return false;
        }
        
        const requiredFields = this.form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        // Validaciones específicas del negocio
        const estado = document.getElementById('estado')?.value;
        const cantord = parseFloat(document.getElementById('cantord')?.value || 0);
        const cantdpx = parseFloat(document.getElementById('cantdpx')?.value || 0);

        if (estado === 'ENTREGADO') {
            if (cantord <= 0) {
                this.showFieldError(document.getElementById('cantord'), 'La cantidad ordenada debe ser mayor a cero');
                isValid = false;
            }
            if (cantdpx <= 0) {
                this.showFieldError(document.getElementById('cantdpx'), 'La cantidad entregada debe ser mayor a cero');
                isValid = false;
            }
        }

        return isValid;
    }

    async handleSubmit(event) {
        event.preventDefault();

        if (!this.validateForm()) {
            this.showAlert('Por favor corrige los errores en el formulario', 'danger');
            return;
        }

        const pendienteId = document.getElementById('hidden_id')?.value;
        if (!pendienteId) {
            this.showAlert('Error: No se encontró el ID del pendiente', 'danger');
            return;
        }

        // Mostrar confirmación
        const estado = document.getElementById('estado')?.value;
        let confirmText = 'Estás por actualizar este registro pendiente';
        
        if (estado === 'ENTREGADO') {
            confirmText = 'Estás por marcar este medicamento como ENTREGADO';
        } else if (estado === 'ANULADO') {
            confirmText = 'Estás por ANULAR este registro pendiente';
        }

        if (!await this.showConfirmation('¿Estás seguro?', confirmText)) {
            return;
        }

        await this.submitForm(pendienteId);
    }

    async submitForm(pendienteId) {
        this.setLoading(true);

        try {
            const formData = new FormData(this.form);
            
            const response = await fetch(`/medcol6/pendientes/${pendienteId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                  document.querySelector('input[name="_token"]')?.value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(Object.fromEntries(formData))
            });

            const result = await response.json();

            if (response.ok && result.success) {
                this.showAlert('Registro actualizado exitosamente', 'success');
                
                // Recargar tabla si existe
                if (typeof window.pendientesTable !== 'undefined' && window.pendientesTable.ajax) {
                    window.pendientesTable.ajax.reload();
                }
                
                // Cerrar modal después de 2 segundos
                setTimeout(() => {
                    $('#modal-edit-pendientes').modal('hide');
                }, 2000);
                
            } else {
                throw new Error(result.message || 'Error al procesar la solicitud');
            }

        } catch (error) {
            console.error('Error en submit:', error);
            
            if (error.errors) {
                // Mostrar errores de validación
                this.showValidationErrors(error.errors);
            } else {
                this.showAlert(`Error: ${error.message}`, 'danger');
            }
        } finally {
            this.setLoading(false);
        }
    }

    showValidationErrors(errors) {
        let errorMessage = 'Errores de validación:<ul>';
        
        if (Array.isArray(errors)) {
            errors.forEach(error => {
                errorMessage += `<li>${error}</li>`;
            });
        } else {
            Object.keys(errors).forEach(field => {
                errors[field].forEach(error => {
                    errorMessage += `<li>${error}</li>`;
                });
            });
        }
        
        errorMessage += '</ul>';
        this.showAlert(errorMessage, 'danger');
    }

    setLoading(isLoading) {
        if (isLoading) {
            this.form.classList.add('form-loading');
            this.submitButton.disabled = true;
            this.submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        } else {
            this.form.classList.remove('form-loading');
            this.submitButton.disabled = false;
            this.submitButton.innerHTML = '<i class="fas fa-save"></i> Guardar Cambios';
        }
    }

    resetForm() {
        if (!this.form) {
            console.warn('⚠️ No se puede resetear: formulario no encontrado');
            return;
        }
        
        this.form.reset();
        this.form.querySelectorAll('.is-valid, .is-invalid').forEach(field => {
            field.classList.remove('is-valid', 'is-invalid');
        });
        this.form.querySelectorAll('.invalid-feedback').forEach(error => {
            error.remove();
        });
        this.clearAlert();
        this.calculatePendingQuantity();
        this.handleStatusChange();
    }

    showAlert(message, type = 'info') {
        if (!this.alertContainer) return;

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-modern alert-${type} alert-dismissible`;
        alertDiv.innerHTML = `
            <i class="fas fa-${this.getAlertIcon(type)}"></i>
            <div>${message}</div>
            <button type="button" class="btn-close" data-dismiss="alert">&times;</button>
        `;

        this.alertContainer.innerHTML = '';
        this.alertContainer.appendChild(alertDiv);

        // Auto-dismiss after 5 seconds for success messages
        if (type === 'success') {
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
    }

    clearAlert() {
        if (this.alertContainer) {
            this.alertContainer.innerHTML = '';
        }
    }

    getAlertIcon(type) {
        const icons = {
            success: 'check-circle',
            danger: 'exclamation-triangle',
            warning: 'exclamation-circle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    async showConfirmation(title, text) {
        return new Promise((resolve) => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Sí, continuar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    resolve(result.isConfirmed);
                });
            } else {
                resolve(confirm(`${title}\n\n${text}`));
            }
        });
    }

    applySelectStyles(estado) {
        // Obtener los contenedores de Select2 específicos del modal
        const select2Container = $('#modal-edit-pendientes .select2-selection--single');
        const select2Rendered = $('#modal-edit-pendientes .select2-selection__rendered');
        
        if (select2Container.length && select2Rendered.length) {
            // Limpiar clases anteriores
            const estadoClasses = ['estado-pendiente', 'estado-entregado', 'estado-desabastecido', 'estado-anulado'];
            select2Container.removeClass(estadoClasses.join(' '));
            select2Rendered.removeClass(estadoClasses.join(' '));
            
            // Aplicar clase según el estado con animación
            const addClass = (className) => {
                select2Container.addClass(className);
                select2Rendered.addClass(className);
                
                // Micro-interacción: efecto de "bounce"
                select2Container.css('transform', 'scale(1.02)');
                setTimeout(() => {
                    select2Container.css('transform', 'scale(1)');
                }, 150);
            };
            
            switch (estado) {
                case 'PENDIENTE':
                    addClass('estado-pendiente');
                    break;
                case 'ENTREGADO':
                    addClass('estado-entregado');
                    break;
                case 'DESABASTECIDO':
                    addClass('estado-desabastecido');
                    break;
                case 'ANULADO':
                    addClass('estado-anulado');
                    break;
            }
            
            console.log('🎨 Estilos aplicados para estado:', estado);
        } else {
            console.warn('⚠️ Contenedores Select2 no encontrados para aplicar estilos');
        }
    }

    setupFieldSynchronization() {
        // Sincronizar campos inline con campos principales del formulario
        const synchronizationMap = {
            'doc_entrega_inline': 'doc_entrega',
            'factura_entrega_inline': 'factura_entrega'
        };

        Object.entries(synchronizationMap).forEach(([inlineFieldId, mainFieldId]) => {
            const inlineField = document.getElementById(inlineFieldId);
            const mainField = document.getElementById(mainFieldId);
            
            if (inlineField && mainField) {
                // Sincronizar del campo inline al principal
                inlineField.addEventListener('input', () => {
                    mainField.value = inlineField.value;
                    console.log(`🔄 Sincronizado ${inlineFieldId} -> ${mainFieldId}: ${inlineField.value}`);
                });
                
                // Sincronizar del campo principal al inline (al cargar datos)
                const observer = new MutationObserver(() => {
                    if (mainField.value !== inlineField.value) {
                        inlineField.value = mainField.value;
                    }
                });
                
                observer.observe(mainField, {
                    attributes: true,
                    attributeFilter: ['value']
                });
            }
        });
    }

    // Método para poblar el formulario con datos
    populateForm(data) {
        if (!data) {
            console.warn('⚠️ No hay datos para poblar el formulario');
            return;
        }
        
        console.log('📝 Poblando formulario con datos:', data);
        
        Object.keys(data).forEach(key => {
            const field = document.getElementById(key);
            if (field) {
                field.value = data[key] || '';
            }
        });

        // Configurar ID oculto
        const hiddenIdField = document.getElementById('hidden_id');
        if (hiddenIdField && data.id) {
            hiddenIdField.value = data.id;
        }

        // Cargar saldo del medicamento
        if (data.codigo && data.centroproduccion) {
            this.loadMedicamentoSaldo(data.codigo, data.centroproduccion);
        }

        // Recalcular cantidades y manejar estados
        this.calculatePendingQuantity();
        this.handleStatusChange();
        
        // Aplicar estilos visuales al select si hay estado
        if (data.estado) {
            this.applySelectStyles(data.estado);
        }
        
        console.log('✅ Formulario poblado exitosamente');
    }

    // Método para cargar el saldo del medicamento específico
    async loadMedicamentoSaldo(codigo, centroproduccion) {
        try {
            // Limpiar y validar los parámetros
            const codigoLimpio = codigo ? codigo.toString().trim() : '';
            const centroproduccionLimpio = centroproduccion ? centroproduccion.toString().trim() : '';
            
            if (!codigoLimpio || !centroproduccionLimpio) {
                console.warn('⚠️ Parámetros inválidos para cargar saldo:', { codigo: codigoLimpio, centroproduccion: centroproduccionLimpio });
                this.setSaldoField(0, 'Parámetros inválidos', 'badge-warning');
                return;
            }
            
            console.log('🔍 Cargando saldo para medicamento específico:', {
                codigo: codigoLimpio, 
                centroproduccion: centroproduccionLimpio
            });
            
            // Mostrar indicador de carga
            this.setSaldoField('...', 'Consultando...', 'badge-info');
            
            const response = await fetch('/medcol6/saldo-medicamento', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                  document.querySelector('input[name="_token"]')?.value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    codigo: codigoLimpio,
                    deposito: centroproduccionLimpio
                })
            });

            const result = await response.json();
            console.log('📊 Respuesta del servidor:', result);

            if (response.ok && result.success) {
                const saldoValue = parseFloat(result.saldo) || 0;
                console.log('✅ Saldo cargado exitosamente:', {
                    saldo: saldoValue,
                    estado: result.estado,
                    medicamento: result.nombre_medicamento,
                    deposito: result.deposito,
                    fecha: result.fecha_saldo
                });
                
                // Actualizar campo y badge
                if (saldoValue > 0) {
                    this.setSaldoField(saldoValue, `Disponible: ${saldoValue} unidades`, 'badge-success');
                } else {
                    const mensaje = result.estado === 'SIN REGISTRO' ? 'Sin registro en inventario' : 'Sin saldo disponible';
                    this.setSaldoField(0, mensaje, 'badge-danger');
                }
                
            } else {
                console.warn('⚠️ Error en respuesta del servidor:', result.message || 'Respuesta inválida');
                this.setSaldoField(0, 'Error al consultar', 'badge-warning');
            }
            
        } catch (error) {
            console.error('❌ Error al cargar saldo del medicamento:', error);
            this.setSaldoField(0, 'Error de conexión', 'badge-danger');
        }
    }
    
    // Método auxiliar unificado para actualizar el campo de saldo y su badge
    setSaldoField(valor, mensajeBadge, classBadge) {
        const saldoField = document.getElementById('saldo_medicamento');
        if (saldoField) {
            saldoField.value = valor;
            
            // Actualizar el badge usando la función unificada
            this.updateSaldoBadge(saldoField, mensajeBadge, classBadge);
        }
    }

    // Función unificada para manejar badges de saldo - disponible globalmente
    updateSaldoBadge(saldoField, mensajeBadge, classBadge) {
        if (!saldoField) return;
        
        const saldoBadge = saldoField.parentNode.querySelector('.saldo-badge');
        if (saldoBadge) {
            // Limpiar todas las clases de estado anteriores
            const stateClasses = ['badge-success', 'badge-warning', 'badge-danger', 'badge-info'];
            saldoBadge.classList.remove(...stateClasses);
            
            // Aplicar nueva clase y mensaje
            saldoBadge.classList.add(classBadge);
            saldoBadge.textContent = mensajeBadge;
            
            console.log(`🎨 Badge actualizado: ${classBadge} - ${mensajeBadge}`);
        }
    }

    // Hacer la función disponible globalmente para compatibilidad
    static updateSaldoBadgeGlobal(saldoFieldId, mensajeBadge, classBadge) {
        const saldoField = document.getElementById(saldoFieldId);
        if (saldoField && window.pendientesFormManager) {
            window.pendientesFormManager.updateSaldoBadge(saldoField, mensajeBadge, classBadge);
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Esperar un poco para asegurar que jQuery y otros scripts estén cargados
    setTimeout(() => {
        // Diagnóstico de elementos del DOM
        console.log('🔍 Diagnóstico de elementos del DOM:');
        console.log('- Formulario #documentManagementForm:', document.getElementById('documentManagementForm'));
        console.log('- Botón #guardar_pendiente:', document.getElementById('guardar_pendiente'));
        console.log('- Botón #limpiar_form:', document.getElementById('limpiar_form'));
        console.log('- Container #alert-container:', document.getElementById('alert-container'));
        console.log('- Select #estado:', document.getElementById('estado'));
        console.log('- Modal #modal-edit-pendientes:', document.getElementById('modal-edit-pendientes'));
        
        window.pendientesFormManager = new PendientesFormManager();
        console.log('✅ PendientesFormManager inicializado');
        
        // Verificar que el sistema de estados funcione
        const estadoSelect = document.getElementById('estado');
        if (estadoSelect) {
            console.log('✅ Select de estado encontrado');
            
            // Verificar si existe la función original
            if (typeof mostrarOcultarCampos === 'function') {
                console.log('✅ Función mostrarOcultarCampos encontrada');
            } else {
                console.warn('⚠️ Función mostrarOcultarCampos NO encontrada');
            }
        } else {
            console.warn('⚠️ Select de estado NO encontrado');
        }
    }, 500);
});

// Función global para abrir el modal con datos
window.openPendienteModal = function(data) {
    console.log('🔄 Abriendo modal con datos:', data);
    
    // Abrir el modal primero
    $('#modal-edit-pendientes').modal('show');
    
    // Esperar un momento para que el modal se renderice completamente
    setTimeout(() => {
        // Reinicializar el manager si es necesario
        if (window.pendientesFormManager && !window.pendientesFormManager.form) {
            console.log('🔄 Reintentando inicialización del FormManager...');
            window.pendientesFormManager.form = document.getElementById('documentManagementForm');
            window.pendientesFormManager.submitButton = document.getElementById('guardar_pendiente');
            window.pendientesFormManager.resetButton = document.getElementById('limpiar_form');
            window.pendientesFormManager.alertContainer = document.getElementById('alert-container');
            
            if (window.pendientesFormManager.form) {
                window.pendientesFormManager.bindEvents();
                window.pendientesFormManager.setupFormValidation();
                window.pendientesFormManager.setupQuantityCalculation();
                window.pendientesFormManager.setupStatusHandling();
                console.log('✅ FormManager reinicializado exitosamente');
            }
        }
        
        if (window.pendientesFormManager && window.pendientesFormManager.form) {
            window.pendientesFormManager.populateForm(data);
            window.pendientesFormManager.clearAlert();
        } else {
            console.warn('⚠️ FormManager no disponible para poblar datos');
        }
    }, 100);
    
    // Forzar la evaluación del estado inicial después de abrir el modal
    setTimeout(() => {
        const estado = document.getElementById('estado')?.value;
        
        // Asegurar que Select2 esté inicializado correctamente
        if (typeof $.fn.select2 !== 'undefined' && $('#estado').length) {
            try {
                // Si Select2 no está inicializado, inicializarlo con configuración completa
                if (!$('#estado').hasClass('select2-hidden-accessible')) {
                    $('#estado').select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        placeholder: '---Seleccione Estado---',
                        allowClear: false,
                        dropdownParent: $('#modal-edit-pendientes'),
                        templateResult: function(option) {
                            if (!option.id) return option.text;
                            
                            // Crear elemento con icono y texto
                            const $option = $('<span></span>');
                            $option.html(option.text);
                            
                            // Agregar clase CSS según el estado
                            if (option.id === 'PENDIENTE') {
                                $option.addClass('select2-option-pendiente');
                            } else if (option.id === 'ENTREGADO') {
                                $option.addClass('select2-option-entregado');
                            } else if (option.id === 'DESABASTECIDO') {
                                $option.addClass('select2-option-desabastecido');
                            } else if (option.id === 'ANULADO') {
                                $option.addClass('select2-option-anulado');
                            }
                            
                            return $option;
                        },
                        templateSelection: function(option) {
                            if (!option.id) return option.text;
                            
                            // Crear elemento para la selección con icono y texto
                            const $selection = $('<span></span>');
                            $selection.html(option.text);
                            
                            return $selection;
                        }
                    });
                    
                    console.log('✅ Select2 inicializado correctamente desde pendientes-form.js');
                }
                
                // Actualizar la selección
                $('#estado').val(estado).trigger('change.select2');
            } catch (e) {
                console.warn('⚠️ Error al inicializar Select2:', e);
            }
        }
        
        if (estado && typeof mostrarOcultarCampos === 'function') {
            console.log('🔄 Aplicando estado inicial:', estado);
            mostrarOcultarCampos(estado);
        }
        
        // Aplicar estilos visuales si tenemos el manager
        if (window.pendientesFormManager && estado) {
            window.pendientesFormManager.applySelectStyles(estado);
        }
    }, 300);
};

// Función global para compatibilidad con indexAnalista.blade.php
window.setSaldoFieldIndependiente = function(valor, mensajeBadge, classBadge) {
    const saldoField = document.getElementById('saldo_medicamento');
    if (saldoField && window.pendientesFormManager) {
        window.pendientesFormManager.setSaldoField(valor, mensajeBadge, classBadge);
    } else {
        // Fallback si el manager no está disponible
        console.warn('⚠️ PendientesFormManager no disponible, usando método fallback');
        if (saldoField) {
            saldoField.value = valor;
            const saldoBadge = saldoField.parentNode.querySelector('.saldo-badge');
            if (saldoBadge) {
                const stateClasses = ['badge-success', 'badge-warning', 'badge-danger', 'badge-info'];
                saldoBadge.classList.remove(...stateClasses);
                saldoBadge.classList.add(classBadge);
                saldoBadge.textContent = mensajeBadge;
            }
        }
    }
};