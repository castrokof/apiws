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
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
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

        </div>
    </div>
</div>

<!-- CSS adicional para asegurar el correcto funcionamiento -->
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
        // Manejar el botón de maximizar
        $('[data-card-widget="maximize"]').click(function(e) {
            e.preventDefault();
            const card = $(this).closest('.card');
            card.toggleClass('maximized-card');

            // Cambiar ícono
            $(this).find('i')
                .toggleClass('fa-expand')
                .toggleClass('fa-compress');
        });

        // Asegurar que el modal se cierre correctamente
        $('#modalIndicadores').on('hidden.bs.modal', function() {
            $(this).find('.card').removeClass('maximized-card');
            $(this).find('[data-card-widget="maximize"] i')
                .removeClass('fa-compress')
                .addClass('fa-expand');
        });
    });
</script>