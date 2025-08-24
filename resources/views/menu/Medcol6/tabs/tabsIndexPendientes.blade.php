<div class="row">
    <div class="col-12">
        <div class="tabs-container">
            <div class="nav-tabs-wrapper p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-datos-med-pendiente-tab" data-toggle="pill"
                        href="#custom-tabs-one-datos-med-pendiente" data-target="#custom-tabs-one-datos-med-pendiente"
                         role="tab" aria-controls="custom-tabs-one-datos-med-pendiente" aria-selected="true">Detalle del Pendiente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-datos-observaciones-tab" data-toggle="pill"
                        href="#custom-tabs-one-datos-observaciones" data-target="#custom-tabs-one-datos-observaciones"
                        role="tab" aria-controls="custom-tabs-one-datos-observaciones" aria-selected="false">Observaciones</a>
                    </li>
                </ul>
            </div>
            <div class="tabs-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-one-datos-med-pendiente" role="tabpanel" aria-labelledby="custom-tabs-one-datos-med-pendiente-tab">
                        <div class="tab-content-wrapper">
                            @include('menu.Medcol6.form.form')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-datos-observaciones" role="tabpanel" aria-labelledby="custom-tabs-one-datos-observaciones-tab">
                        <div class="tab-content-wrapper">
                            @include('menu.Medcol6.tablas.tablaObservaciones')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS para estilos del modal y formulario en tabsIndexPendientes-->
<style>
    .tabs-container {
        background: #fff;
        border-radius: 0.25rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    }
    
    .nav-tabs-wrapper {
        background: #f4f4f4;
        border-bottom: 1px solid #dee2e6;
        border-radius: 0.25rem 0.25rem 0 0;
    }
    
    .tabs-body {
        padding: 1rem;
    }
    
    .tab-content-wrapper {
        padding: 0;
    }

    /* Modern UI/UX Styles for documentManagementForm */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    /* Global styles for the modal */
    #modal-edit-pendientes {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    #documentManagementForm {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        color: #1f2937;
        line-height: 1.6;
    }

    .modal-section {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        margin-bottom: 24px;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modal-section:hover {
        box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transform: translateY(-1px);
    }

    .modal-section-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 24px;
        font-weight: 600;
        font-size: 16px;
        letter-spacing: -0.025em;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        border: none;
    }

    .modal-section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: rgba(255, 255, 255, 0.2);
    }

    .modal-section-title i {
        font-size: 18px;
        opacity: 0.9;
    }

    .modal-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        padding: 32px 24px;
        background: linear-gradient(180deg, #fafbfc 0%, #ffffff 100%);
    }

    .modal-form-group {
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .modal-form-group.half-width {
        grid-column: span 1;
    }

    .modal-form-group.full-width {
        grid-column: 1 / -1;
    }

    .modal-form-group label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
        font-size: 14px;
        letter-spacing: -0.025em;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .modal-form-group label.required::after {
        content: "*";
        color: #ef4444;
        font-weight: 600;
        font-size: 16px;
        margin-left: 2px;
    }

    .modal-form-group input,
    .modal-form-group select,
    .modal-form-group textarea {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        background: #ffffff;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: inherit;
        color: #1f2937;
    }

    .modal-form-group input:focus,
    .modal-form-group select:focus,
    .modal-form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: #ffffff;
        transform: translateY(-1px);
    }

    .modal-form-group input[readonly] {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        color: #64748b;
        border-color: #cbd5e1;
        cursor: not-allowed;
    }

    .modal-form-group input[readonly]:focus {
        transform: none;
        box-shadow: none;
    }

    .modal-quantity-badge {
        font-size: 12px;
        color: #6b7280;
        margin-top: 6px;
        font-weight: 400;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .modal-quantity-badge::before {
        content: "ℹ️";
        font-size: 14px;
    }

    /* NOTA: Los estilos de .saldo-badge ahora están centralizados en modal-form.css
       para evitar duplicación y conflictos. Mantener solo estilos específicos del contexto aquí. */

    .modal-status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        padding: 24px;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border-top: 1px solid #e5e7eb;
    }

    .modal-status-item {
        padding: 20px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        background: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .modal-status-item:hover {
        border-color: #667eea;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .modal-status-item.hidden {
        display: none;
    }

    .modal-status-item label {
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
        display: block;
        font-size: 14px;
        letter-spacing: -0.025em;
    }

    .modal-management-section .modal-section-title {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .modal-user-info .modal-section-title {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }

    .modal-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px;
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        border-top: 1px solid #e5e7eb;
        gap: 12px;
    }

    .modal-actions .btn {
        min-width: 140px;
        font-weight: 500;
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        letter-spacing: -0.025em;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .modal-actions .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .modal-actions .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .modal-actions .btn-secondary {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }

    .modal-actions .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    /* Modern Select2 custom styles */
    .select2-container--bootstrap4 .select2-selection--single {
        height: 48px !important;
        border: 2px solid #e5e7eb !important;
        border-radius: 12px !important;
        background: #ffffff;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .select2-container--bootstrap4 .select2-selection--single:hover {
        border-color: #d1d5db !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .select2-container--bootstrap4.select2-container--focus .select2-selection--single {
        border-color: #667eea !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }

    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        color: #1f2937;
        line-height: 44px;
        padding-left: 16px;
        padding-right: 40px;
        font-size: 14px;
        font-weight: 500;
    }

    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
        height: 44px;
        right: 12px;
    }

    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow b {
        border-color: #6b7280 transparent transparent transparent;
        border-style: solid;
        border-width: 6px 6px 0 6px;
        height: 0;
        left: 50%;
        margin-left: -6px;
        margin-top: -3px;
        position: absolute;
        top: 50%;
        width: 0;
    }

    /* Estados del select con gradients modernos */
    .estado-pendiente {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%) !important;
        color: #92400e !important;
        border-color: #f59e0b !important;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2) !important;
    }

    .estado-entregado {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%) !important;
        color: #047857 !important;
        border-color: #10b981 !important;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2) !important;
    }

    .estado-desabastecido {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%) !important;
        color: #b91c1c !important;
        border-color: #ef4444 !important;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2) !important;
    }

    .estado-anulado {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%) !important;
        color: #374151 !important;
        border-color: #6b7280 !important;
        box-shadow: 0 2px 8px rgba(107, 114, 128, 0.2) !important;
    }

    /* Select2 dropdown styles */
    .select2-container--bootstrap4 .select2-dropdown {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        margin-top: 4px;
    }

    .select2-container--bootstrap4 .select2-results__option {
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.15s ease;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .select2-container--bootstrap4 .select2-results__option:last-child {
        border-bottom: none;
    }

    .select2-container--bootstrap4 .select2-results__option[aria-selected="true"] {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 500;
    }

    .select2-container--bootstrap4 .select2-results__option--highlighted {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #1f2937;
    }

    /* Enhanced dropdown animation */
    .select2-container--bootstrap4 .select2-dropdown.enhanced-dropdown {
        animation: dropdownSlide 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: top;
    }

    @keyframes dropdownSlide {
        0% {
            opacity: 0;
            transform: scaleY(0.8) translateY(-10px);
        }
        100% {
            opacity: 1;
            transform: scaleY(1) translateY(0);
        }
    }

    /* Error tooltips */
    .error-tooltip {
        position: absolute;
        bottom: -25px;
        left: 0;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        z-index: 1000;
        animation: fadeInUp 0.3s ease;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .error-tooltip::before {
        content: '';
        position: absolute;
        top: -4px;
        left: 12px;
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-bottom: 4px solid #ef4444;
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Enhanced state classes */
    .modal-form-group.has-pending label {
        color: #f59e0b;
    }

    .modal-form-group.has-pending input {
        border-color: #f59e0b;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 20%, #ffffff 100%);
    }

    .modal-form-group.has-completed label {
        color: #10b981;
    }

    .modal-form-group.has-completed input {
        border-color: #10b981;
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 20%, #ffffff 100%);
    }

    /* Select2 option styling */
    .select2-option-pendiente {
        color: #92400e !important;
        font-weight: 500;
    }

    .select2-option-entregado {
        color: #047857 !important;
        font-weight: 500;
    }

    .select2-option-desabastecido {
        color: #b91c1c !important;
        font-weight: 500;
    }

    .select2-option-anulado {
        color: #374151 !important;
        font-weight: 500;
    }

    /* Animation effects */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }

    .modal-form-group input:focus + .modal-quantity-badge,
    .modal-form-group select:focus + .modal-quantity-badge {
        animation: pulse 2s infinite;
        color: #667eea;
    }

    /* Loading state for select */
    .select2-container--bootstrap4.select2-container--loading .select2-selection--single .select2-selection__arrow {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    /* Form validation states */
    .modal-form-group.has-error input,
    .modal-form-group.has-error select {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .modal-form-group.has-success input,
    .modal-form-group.has-success select {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* Progress indicator */
    .form-progress {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        transition: width 0.3s ease;
        z-index: 9999;
    }

    /* Enhanced focus states with smooth transitions */
    .modal-form-group.focused {
        transform: translateY(-1px);
        transition: transform 0.2s ease;
    }

    .modal-form-group.focused label {
        color: #667eea;
        font-weight: 600;
    }

    .modal-form-group.has-value label {
        color: #10b981;
        font-weight: 500;
    }

    /* Loading states for form submission */
    .modal-actions .btn.loading {
        position: relative;
        color: transparent;
        pointer-events: none;
    }

    .modal-actions .btn.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Enhanced button interactions */
    .modal-actions .btn:active {
        transform: translateY(0) scale(0.98);
    }

    .modal-actions .btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
    }

    /* Form validation feedback */
    .modal-form-group.has-success::after {
        content: '✓';
        position: absolute;
        right: 12px;
        top: 50%;
        color: #10b981;
        font-weight: bold;
        animation: checkmark 0.3s ease-in-out;
    }

    .modal-form-group.has-error::after {
        content: '⚠';
        position: absolute;
        right: 12px;
        top: 50%;
        color: #ef4444;
        font-weight: bold;
        animation: errormark 0.3s ease-in-out;
    }

    @keyframes checkmark {
        0% { transform: scale(0) rotate(-45deg); }
        50% { transform: scale(1.2) rotate(-45deg); }
        100% { transform: scale(1) rotate(-45deg); }
    }

    @keyframes errormark {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* NOTA: Los efectos hover de .saldo-badge también están centralizados en modal-form.css */

    /* Responsive adjustments with improved mobile experience */
    @media (max-width: 768px) {
        .modal-form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
            padding: 16px;
        }

        .modal-form-group.half-width {
            grid-column: span 1;
        }

        .modal-status-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .modal-actions {
            flex-direction: column;
            gap: 12px;
            padding: 20px;
        }

        .modal-actions .btn {
            width: 100%;
            min-height: 48px;
            font-size: 16px;
        }

        .modal-section-title {
            padding: 16px 20px;
            font-size: 15px;
        }

        .select2-container--bootstrap4 .select2-selection--single {
            height: 52px !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            line-height: 48px;
            font-size: 16px;
        }
    }

    @media (max-width: 480px) {
        .modal-form-grid {
            padding: 12px;
            gap: 12px;
        }

        .modal-section-title {
            padding: 12px 16px;
            font-size: 14px;
        }

        .modal-actions .btn {
            min-height: 44px;
            font-size: 15px;
        }
    }
</style>
