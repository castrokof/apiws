<!-- Modal compartido para imagen o PDF -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Vista previa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <!-- Imagen -->
        <img id="modalImage" src="" alt="Imagen" class="img-fluid" style="display: none; max-height: 80vh;">

        <!-- PDF -->
        <iframe id="modalPdf" src="" frameborder="0" style="width: 100%; height: 80vh; display: none;"></iframe>
      </div>
    </div>
  </div>
</div>