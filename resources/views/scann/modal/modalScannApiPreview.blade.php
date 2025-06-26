<!-- Modal de Previsualización -->
<div class="modal fade" id="modal_preview" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto Capturada</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <div id="previewContainer"></div>
                <input type="text" id="codigo" placeholder="Código extraído" readonly class="form-control mt-2">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tomar Otra Foto</button>
                <button type="button" class="btn btn-primary" onclick="enviarFotos()">Confirmar y Enviar</button>
            </div>
        </div>
    </div>
</div>