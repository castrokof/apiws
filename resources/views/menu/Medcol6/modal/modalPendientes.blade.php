<!-- Modal con diseño moderno aplicado -->
<div class="modal fade" id="modal-edit-pendientes" tabindex="-1" role="dialog" aria-labelledby="edit_pendiente" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content card"> <!-- se aplica clase .card aquí -->
            <!-- Header del Modal -->
            <div class="modal-header card-header">
                <h5 class="modal-title" id="edit_pendiente">
                    <i class="fas fa-file-medical"></i>
                    Gestión de Documentos Pendientes
                </h5>
                <div class="card-tools ml-auto">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Body del Modal -->
            <div class="card-body modal-body">
                <!-- Mensajes de error y éxito -->
                <div class="modal-messages">
                    @include('includes.form-error')
                    @include('includes.form-mensaje')
                    <span id="form_result"></span>
                </div>

                <!-- Formulario Principal -->
                <form id="form-general1" class="form-horizontal modal-form">
                    @csrf
                    <div class="modal-form-container">
                        @include('menu.Medcol6.tabs.tabsIndexPendientes')
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- CSS adicional -->
<style>
    /* Asegurar que la card ocupe todo el espacio del modal */
    .modal-content .card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .modal-content .card-body {
        flex: 1;
        overflow-y: auto;
    }

    /* Estilos para el modo maximizado */
    .modal-content .card.maximized-card {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1060;
        margin: 0;
        width: 100vw;
        height: 100vh;
    }
</style>

<script>
    $(document).ready(function() {
        // Manejador específico para este modal solamente
        $('#modal-edit-pendientes [data-card-widget="maximize"]').off('click').click(function(e) {
            e.preventDefault();
            const card = $(this).closest('.card');
            const isMaximized = card.hasClass('maximized-card');
            
            if (isMaximized) {
                card.removeClass('maximized-card');
                $(this).find('i').removeClass('fa-compress').addClass('fa-expand');
            } else {
                card.addClass('maximized-card');
                $(this).find('i').removeClass('fa-expand').addClass('fa-compress');
            }
        });

        // Manejador específico para collapse de este modal
        $('#modal-edit-pendientes [data-card-widget="collapse"]').off('click').click(function(e) {
            e.preventDefault();
            const cardBody = $(this).closest('.card').find('.card-body');
            const isCollapsed = cardBody.is(':hidden');
            
            if (isCollapsed) {
                cardBody.show();
                $(this).find('i').removeClass('fa-plus').addClass('fa-minus');
            } else {
                cardBody.hide();
                $(this).find('i').removeClass('fa-minus').addClass('fa-plus');
            }
        });

        // Asegurar que el modal se cierre correctamente
        $('#modal-edit-pendientes').on('hidden.bs.modal', function() {
            const card = $(this).find('.card');
            card.removeClass('maximized-card');
            card.find('.card-body').show();
            card.find('[data-card-widget="maximize"] i')
                .removeClass('fa-compress')
                .addClass('fa-expand');
            card.find('[data-card-widget="collapse"] i')
                .removeClass('fa-plus')
                .addClass('fa-minus');
        });

        // Inicialización unificada de Select2 - se maneja desde pendientes-form.js
        // Esta sección se ha movido para evitar conflictos con el sistema unificado

        // Funciones de estilo y notificaciones - ahora se manejan desde pendientes-form.js
        // Se mantienen aquí las funciones de toast para compatibilidad
        window.aplicarEstilosSelect = function(estado) {
            // Delegamos al sistema unificado si está disponible
            if (window.pendientesFormManager && typeof window.pendientesFormManager.applySelectStyles === 'function') {
                window.pendientesFormManager.applySelectStyles(estado);
                
                // Agregar notificaciones adicionales
                switch (estado) {
                    case 'ENTREGADO':
                        mostrarNotificacionExito('Estado actualizado a Entregado');
                        break;
                    case 'DESABASTECIDO':
                        mostrarNotificacionAdvertencia('Medicamento marcado como Desabastecido');
                        break;
                    case 'ANULADO':
                        mostrarNotificacionInfo('Registro marcado como Anulado');
                        break;
                }
            }
        };

        // Función para mostrar notificaciones toast modernas
        function mostrarNotificacionExito(mensaje) {
            mostrarToast(mensaje, 'success', '✅');
        }

        function mostrarNotificacionAdvertencia(mensaje) {
            mostrarToast(mensaje, 'warning', '⚠️');
        }

        function mostrarNotificacionInfo(mensaje) {
            mostrarToast(mensaje, 'info', 'ℹ️');
        }

        function mostrarToast(mensaje, tipo, icono) {
            // Crear toast element
            const toast = $(`
                <div class="modern-toast toast-${tipo}" style="
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: linear-gradient(135deg, ${getToastGradient(tipo)});
                    color: white;
                    padding: 12px 20px;
                    border-radius: 10px;
                    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
                    z-index: 10000;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    font-weight: 500;
                    font-size: 14px;
                    max-width: 350px;
                    opacity: 0;
                    transform: translateX(100px);
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                ">
                    <span style="font-size: 16px;">${icono}</span>
                    <span>${mensaje}</span>
                </div>
            `);

            $('body').append(toast);

            // Animación de entrada
            setTimeout(() => {
                toast.css({
                    opacity: 1,
                    transform: 'translateX(0)'
                });
            }, 10);

            // Remover después de 3 segundos
            setTimeout(() => {
                toast.css({
                    opacity: 0,
                    transform: 'translateX(100px)'
                });
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function getToastGradient(tipo) {
            switch (tipo) {
                case 'success': return '#10b981, #059669';
                case 'warning': return '#f59e0b, #d97706';
                case 'info': return '#3b82f6, #2563eb';
                case 'error': return '#ef4444, #dc2626';
                default: return '#6b7280, #4b5563';
            }
        }

        // Enhanced form interactions and validation
        $('#modal-edit-pendientes').on('shown.bs.modal', function() {
            initializeFormEnhancements();
            initializeFormValidation();
        });

        function initializeFormEnhancements() {
            // Enhanced focus and value states
            $('.modal-form-group input, .modal-form-group textarea, .modal-form-group select').each(function() {
                const $input = $(this);
                const $group = $input.closest('.modal-form-group');
                
                $input.on('focus', function() {
                    $group.addClass('focused');
                    showFormProgress(25);
                }).on('blur', function() {
                    $group.removeClass('focused');
                    updateFieldState($input, $group);
                }).on('input change', function() {
                    updateFieldState($input, $group);
                    validateField($input, $group);
                });

                // Check initial values
                updateFieldState($input, $group);
            });

            // Enhanced button interactions
            $('.modal-actions .btn').on('click', function() {
                const $btn = $(this);
                if ($btn.attr('type') === 'submit') {
                    $btn.addClass('loading');
                    showFormProgress(100);
                    
                    // Remove loading state after form processing
                    setTimeout(() => {
                        $btn.removeClass('loading');
                        hideFormProgress();
                    }, 2000);
                }
            });
        }

        function updateFieldState($input, $group) {
            const hasValue = $input.val() && $input.val().toString().trim() !== '';
            $group.toggleClass('has-value', hasValue);
            
            // Add visual feedback for required fields
            const isRequired = $input.prop('required') || $input.closest('.modal-form-group').find('label').hasClass('required');
            if (isRequired && hasValue) {
                $group.removeClass('has-error').addClass('has-success');
            } else if (isRequired && !hasValue) {
                $group.removeClass('has-success has-error');
            }
        }

        function validateField($input, $group) {
            const value = $input.val();
            const isRequired = $input.prop('required') || $group.find('label').hasClass('required');
            const minLength = $input.attr('minlength');
            const type = $input.attr('type');
            
            let isValid = true;
            let errorMessage = '';

            if (isRequired && (!value || value.toString().trim() === '')) {
                isValid = false;
                errorMessage = 'Este campo es requerido';
            } else if (minLength && value && value.length < parseInt(minLength)) {
                isValid = false;
                errorMessage = `Mínimo ${minLength} caracteres`;
            } else if (type === 'number' && value && (isNaN(value) || parseFloat(value) < 0)) {
                isValid = false;
                errorMessage = 'Ingrese un número válido';
            }

            $group.toggleClass('has-error', !isValid);
            $group.toggleClass('has-success', isValid && value);

            // Show/hide error tooltip
            const existingTooltip = $group.find('.error-tooltip');
            if (!isValid && value) {
                if (existingTooltip.length === 0) {
                    $group.append(`<div class="error-tooltip">${errorMessage}</div>`);
                }
            } else {
                existingTooltip.remove();
            }
        }

        function initializeFormValidation() {
            // Real-time quantity calculations
            $('#cantord, #cantdpx').on('input', function() {
                calculatePendingQuantity();
            });

            // Enhanced Select2 with custom styling
            enhanceSelect2Styling();
        }

        function calculatePendingQuantity() {
            const ordered = parseFloat($('#cantord').val()) || 0;
            const delivered = parseFloat($('#cantdpx').val()) || 0;
            const pending = Math.max(0, ordered - delivered);
            
            $('#cant_pndt').val(pending);
            
            // Visual feedback for calculations
            const $pendingGroup = $('#cant_pndt').closest('.modal-form-group');
            if (pending > 0) {
                $pendingGroup.addClass('has-pending');
                $pendingGroup.removeClass('has-completed');
            } else {
                $pendingGroup.addClass('has-completed');
                $pendingGroup.removeClass('has-pending');
            }
        }

        function enhanceSelect2Styling() {
            // Add enhanced visual states to Select2
            $('#estado').on('select2:open', function() {
                $('.select2-container--bootstrap4 .select2-dropdown').addClass('enhanced-dropdown');
            });

            $('#estado').on('select2:close', function() {
                setTimeout(() => {
                    $('.select2-container--bootstrap4 .select2-dropdown').removeClass('enhanced-dropdown');
                }, 300);
            });
        }

        function showFormProgress(percentage) {
            let $progressBar = $('.form-progress');
            if ($progressBar.length === 0) {
                $progressBar = $('<div class="form-progress"></div>');
                $('body').append($progressBar);
            }
            $progressBar.css('width', percentage + '%');
        }

        function hideFormProgress() {
            $('.form-progress').css('width', '0%');
            setTimeout(() => {
                $('.form-progress').remove();
            }, 300);
        }
    });
</script>
