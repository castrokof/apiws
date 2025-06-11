<!-- Modal con diseño moderno aplicado -->
<div class="modal fade modal-modern" tabindex="-1" id="modal-edit-pendientes" role="dialog" aria-labelledby="myLargeModalLabel" hidden.bs.modal="limpiarModal()">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <!-- Header del Modal -->
            <div class="modal-header">
                <h5 class="modal-title" id="edit_pendiente">
                    <i class="fas fa-file-medical"></i>
                    Gestión de Documentos Pendientes
                </h5>
                <div class="modal-header-controls">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize" title="Maximizar">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Minimizar">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Cerrar"></button>
                </div>
            </div>

            <!-- Body del Modal -->
            <div class="modal-body">
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

            <!-- Footer del Modal -->
            <div class="modal-footer">
                <div class="modal-footer-content">
                    @include('includes.boton-form-crear-empresa-empleado-usuario')
                </div>
            </div>
        </div>
    </div>
</div>

