

<div class="form-group">
        <label for="codigo">Código:</label>
        <input type="text" id="codigo" name="codigo" placeholder="Código extraído" required class="form-control mt-2" oninput="this.value = this.value.toUpperCase();">
    </div>
    

    <div class="form-group">
        <label>Fotos del Comprobante:</label>
        <div id="preview_comprobante"></div>
        <button type="button" id="comprobante" name="comprobante" class="btn btn-primary btn-abrir-camara" >Tomar Foto</button>
        
    </div>

    <div class="form-group">
        <label>Fotos de la Orden:</label>
        <div id="preview_orden"></div>
        <button type="button" id="orden" name="orden" class="btn  btn-primary btn-abrir-camara" >Tomar Foto</button>
    </div>

    <div class="form-group">
        <button id="btnConfirmar" id="btnEnviarFotos" class="btn btn-primary mt-2 d-none" >Confirmar y Enviar</button>   
    </div>
    
    <div id="previewContainer" class="mt-3 text-center"></div>