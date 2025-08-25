<!-- Modal Reglas de Gestión -->
<div class="modal fade" id="modalReglasGestion" tabindex="-1" role="dialog" aria-labelledby="modalReglasGestionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalReglasGestionLabel">
                    <i class="fas fa-info-circle mr-2"></i>Guía de Reglas para Gestión de Pendientes
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                
                <!-- Reglas Generales -->
                <section class="mb-4">
                    <h4 class="text-primary mb-3">
                        <i class="fas fa-exclamation-triangle text-warning mr-2"></i>Reglas Generales Siempre Activas
                    </h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h6 class="card-title text-info">Estado del Pendiente</h6>
                                    <p class="card-text">Es <strong>obligatorio</strong> seleccionar un estado válido para cada operación. Este campo determina qué otras reglas se aplicarán.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h6 class="card-title text-info">Cantidad Ordenada</h6>
                                    <p class="card-text">Siempre se debe especificar la cantidad original del Pendiente. Debe ser un <strong>número mayor que cero</strong>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Flujo del Proceso -->
                <section class="mb-4">
                    <h4 class="text-primary mb-3">
                        <i class="fas fa-project-diagram mr-2"></i>El Flujo de un Pendiente
                    </h4>
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <p class="text-muted mb-4">Un Pendiente inicia en estado 'Pendiente' y puede transitar hacia diferentes estados finales dependiendo de las acciones de gestión.</p>
                            <div class="d-flex justify-content-center align-items-center flex-wrap">
                                <span class="badge badge-primary badge-lg px-3 py-2 m-2">INICIO: PENDIENTE</span>
                                <i class="fas fa-arrow-right text-primary mx-2"></i>
                                <div class="d-flex flex-column">
                                    <span class="badge badge-success m-1 px-3 py-2">ENTREGADO</span>
                                    <span class="badge badge-danger m-1 px-3 py-2">ANULADO</span>
                                    <span class="badge badge-warning m-1 px-3 py-2">DESABASTECIDO</span>
                                    <span class="badge badge-secondary m-1 px-3 py-2">SIN CONTACTO</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Detalle de Reglas por Estado -->
                <section class="mb-4">
                    <h4 class="text-primary mb-3">
                        <i class="fas fa-list-check mr-2"></i>Detalle de Reglas por Estado
                    </h4>
                    <div class="row">
                        
                        <!-- Entregado -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0"><i class="fas fa-check-circle mr-1"></i>1. ENTREGADO</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-3">Para Pendientes completados exitosamente. Es el estado con más validaciones.</p>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1"><i class="fas fa-check text-success mr-2"></i><strong>Cant. Entregada:</strong> Requerido, ≥ 1</li>
                                        <li class="mb-1"><i class="fas fa-check text-success mr-2"></i><strong>Fecha Entrega:</strong> Requerida</li>
                                        <li class="mb-1"><i class="fas fa-check text-success mr-2"></i><strong>Factura Entrega:</strong> Requerida</li>
                                        <li class="mb-1"><i class="fas fa-check text-success mr-2"></i><strong>Doc. Entrega:</strong> Requerido</li>
                                        <li class="mb-1"><i class="fas fa-check text-success mr-2"></i><strong>Observaciones:</strong> Requeridas (mín. 3 car.)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Desabastecido -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle mr-1"></i>2. DESABASTECIDO</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-3">Cuando no hay stock para surtir el Pendiente.</p>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1"><i class="fas fa-check text-success mr-2"></i><strong>Fecha Tramitado:</strong> Requerida</li>
                                        <li class="mb-1"><i class="fas fa-circle text-secondary mr-2"></i><strong>Cant. Entregada:</strong> Opcional</li>
                                        <li class="mb-1"><i class="fas fa-circle text-secondary mr-2"></i><strong>Observaciones:</strong> Opcional</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Anulado -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0"><i class="fas fa-times-circle mr-1"></i>3. ANULADO</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-3">Para Pendientes cancelados por cualquier motivo.</p>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1"><i class="fas fa-check text-success mr-2"></i><strong>Fecha Anulación:</strong> Requerida</li>
                                        <li class="mb-1"><i class="fas fa-circle text-secondary mr-2"></i><strong>Cant. Entregada:</strong> Opcional</li>
                                        <li class="mb-1"><i class="fas fa-circle text-secondary mr-2"></i><strong>Observaciones:</strong> Opcional</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Sin Contacto -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <h6 class="mb-0"><i class="fas fa-phone-slash mr-1"></i>4. SIN CONTACTO</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-3">Cuando no se pudo contactar al cliente.</p>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1"><i class="fas fa-check text-success mr-2"></i><strong>Fecha Sin Contacto:</strong> Requerida</li>
                                        <li class="mb-1"><i class="fas fa-circle text-secondary mr-2"></i><strong>Cant. Entregada:</strong> Opcional</li>
                                        <li class="mb-1"><i class="fas fa-circle text-secondary mr-2"></i><strong>Observaciones:</strong> Opcional</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Vencido -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 border-dark">
                                <div class="card-header bg-dark text-white">
                                    <h6 class="mb-0"><i class="fas fa-clock mr-1"></i>5. VENCIDO</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-3">Pendientes que superaron el tiempo límite de gestión.</p>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1"><i class="fas fa-circle text-secondary mr-2"></i><strong>Cant. Entregada:</strong> Opcional</li>
                                        <li class="mb-1"><i class="fas fa-circle text-secondary mr-2"></i><strong>Observaciones:</strong> Opcional</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Pendiente -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-hourglass-half mr-1"></i>6. PENDIENTE</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-3">Estado inicial de todo Pendiente nuevo.</p>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1"><i class="fas fa-circle text-secondary mr-2"></i><strong>Cant. Entregada:</strong> Opcional</li>
                                        <li class="mb-1"><i class="fas fa-circle text-secondary mr-2"></i><strong>Observaciones:</strong> Opcional</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>

                <!-- Leyenda -->
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle mr-2"></i>Leyenda:</h6>
                    <div class="row">
                        <div class="col-6">
                            <i class="fas fa-check text-success mr-2"></i><strong>Campo Requerido</strong> - Obligatorio completar
                        </div>
                        <div class="col-6">
                            <i class="fas fa-circle text-secondary mr-2"></i><strong>Campo Opcional</strong> - No es necesario completar
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>