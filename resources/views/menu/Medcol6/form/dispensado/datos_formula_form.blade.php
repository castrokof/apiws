<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Información de Factura y Médico</h5>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-3 g-3">
                <!-- Columna 1 -->
                <div class="col">
                    <div class="mb-3">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control bg-light" id="ciudad" name="ciudad" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="id_medico" class="form-label">Código Médico</label>
                        <input type="text" class="form-control bg-light" id="id_medico" name="id_medico" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="numeroIdentificacion" class="form-label">Documento ID Médico</label>
                        <input type="text" class="form-control bg-light" id="numeroIdentificacion" name="numeroIdentificacion" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tipoidmedico" class="form-label">Tipo ID Médico</label>
                        <input type="text" class="form-control bg-light" id="tipoidmedico" name="tipoidmedico" readonly>
                    </div>
                </div>

                <!-- Columna 2 -->
                <div class="col">
                    <div class="mb-3">
                        <label for="cobertura2" class="form-label">Cobertura</label>
                        <input type="text" class="form-control bg-light" id="cobertura2" name="cobertura2" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tipoentrega" class="form-label">Tipo de Entrega</label>
                        <input type="text" class="form-control bg-light" id="tipoentrega" name="tipoentrega" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="medico1" class="form-label">Profesional</label>
                        <input type="text" class="form-control bg-light" id="medico1" name="medico1" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="especialidadmedico" class="form-label">Especialidad</label>
                        <input type="text" class="form-control bg-light" id="especialidadmedico" name="especialidadmedico" readonly>
                    </div>
                </div>

                <!-- Columna 3 -->
                <div class="col">
                    <div class="mb-3">
                        <label for="tipocontrato" class="form-label">Tipo Contrato</label>
                        <input type="text" class="form-control bg-light" id="tipocontrato" name="tipocontrato" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="ambito" class="form-label">Ámbito</label>
                        <input type="text" class="form-control bg-light" id="ambito" name="ambito" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="cod_dispensario_sos" class="form-label">Código Dispensario</label>
                        <input type="text" class="form-control bg-light" id="cod_dispensario_sos" name="cod_dispensario_sos" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_solicitud" class="form-label">Fecha Solicitud</label>
                        <input type="date" class="form-control bg-light" id="fecha_solicitud" name="fecha_solicitud" readonly>
                    </div>
                </div>
            </div>

            <!-- Sección de Diagnóstico e IPS (Se oculta si Estado es DISPENSADO) -->
            <div id="diagnostico-ips-section" class="row mt-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="diagnostico2" class="form-label">Diagnóstico</label>
                        <input type="text" class="form-control" id="diagnostico2" name="diagnostico2" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="ips_nombre" class="form-label">IPS</label>
                        <input type="text" class="form-control" id="ips_nombre" name="ips_nombre" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
