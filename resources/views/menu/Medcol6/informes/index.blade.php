@extends('layouts.admin')

@section('title', 'Informes Medcol6')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-chart-bar text-info mr-2"></i>
                    Informes - Medcol
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('medcol6.dispensado.index') }}">Dispensados</a></li>
                    <li class="breadcrumb-item active">Informes</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        <!-- Filtros Principales -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-filter mr-2"></i>
                    Parámetros de Consulta
                </h3>
            </div>
            <div class="card-body">
                <form id="form-filtros-informes">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fechaini">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    Fecha Inicial
                                </label>
                                <input type="date" id="fechaini" name="fechaini" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="fechafin">
                                    <i class="far fa-calendar-check mr-1"></i>
                                    Fecha Final
                                </label>
                                <input type="date" id="fechafin" name="fechafin" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="contrato">
                                    <i class="fas fa-building mr-1"></i>
                                    Farmacia
                                </label>
                                <select name="contrato" id="contrato" class="form-control select2bs4">
                                    <option value="">Todas las farmacias</option>
                                    <optgroup label="Farmacias Principales">
                                        <option value="BIO1">BIO1-FARMACIA BIOLOGICOS</option>
                                        <option value="DLR1">DLR1-FARMACIA DOLOR</option>
                                        <option value="DPA1">DPA1-FARMACIA PALIATIVOS</option>
                                        <option value="EM01">EM01-FARMACIA EMCALI</option>
                                        <option value="EHU1">EHU1-FARMACIA HUERFANAS</option>
                                        <option value="FRJA">FRJA-FARMACIA JAMUNDI</option>
                                        <option value="INY">INY-FARMACIA INYECTABLES</option>
                                        <option value="PAC">PAC-FARMACIA PAC</option>
                                        <option value="SM01">SM01-FARMACIA SALUD MENTAL</option>
                                    </optgroup>
                                    <optgroup label="Farmacias Especializadas">
                                        <option value="BPDT">BPDT-BOLSA</option>
                                        <option value="EVEN">EVEN-FARMACIA EVENTO</option>
                                        <option value="EVSM">EVSM-EVENTO SALUD MENTAL</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cobertura">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    Cobertura
                                </label>
                                <select name="cobertura" id="cobertura" class="form-control select2bs4">
                                    <option value="">Todas</option>
                                    <option value="1">PBS - POS</option>
                                    <option value="2">NOPBS - NOPOS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Grid de Informes Disponibles -->
        <div class="row">

            <!-- Informe Dispensación -->
            <div class="col-lg-4 col-md-6">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-capsules mr-2"></i>
                            Informe Dispensación
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Resumen de medicamentos dispensados con estadísticas por estado (Pendientes, Revisados, Anulados).
                        </p>
                        <ul class="fa-ul text-sm">
                            <li><span class="fa-li"><i class="fas fa-check text-success"></i></span> Revisados</li>
                            <li><span class="fa-li"><i class="fas fa-clock text-primary"></i></span> Sin Revisar</li>
                            <li><span class="fa-li"><i class="fas fa-ban text-danger"></i></span> Anulados</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary btn-block" onclick="cargarInformeDispensacion()">
                            <i class="fas fa-play mr-2"></i>
                            Generar Informe
                        </button>
                    </div>
                </div>
            </div>

            <!-- Informe ForGif -->
            <div class="col-lg-4 col-md-6">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-excel mr-2"></i>
                            Informe FOR_GIF_003
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Informe detallado con información de prestadores, códigos genéricos, valores unitarios y registro sanitario.
                        </p>
                        <ul class="fa-ul text-sm">
                            <li><span class="fa-li"><i class="fas fa-check text-success"></i></span> NIT y Prestador</li>
                            <li><span class="fa-li"><i class="fas fa-check text-success"></i></span> Códigos Genéricos</li>
                            <li><span class="fa-li"><i class="fas fa-check text-success"></i></span> Valores y CUM</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-success btn-block" onclick="cargarInformeForgif()">
                            <i class="fas fa-play mr-2"></i>
                            Generar Informe
                        </button>
                    </div>
                </div>
            </div>

            <!-- Informe Medicamentos -->
            <div class="col-lg-4 col-md-6">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-pills mr-2"></i>
                            Informe Medicamentos
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Listado detallado de medicamentos dispensados con información completa de prescripción.
                        </p>
                        <ul class="fa-ul text-sm">
                            <li><span class="fa-li"><i class="fas fa-info-circle text-info"></i></span> En desarrollo</li>
                            <li><span class="fa-li"><i class="fas fa-info-circle text-info"></i></span> Próximamente</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-info btn-block" disabled>
                            <i class="fas fa-lock mr-2"></i>
                            Próximamente
                        </button>
                    </div>
                </div>
            </div>

            <!-- Informe Insumos -->
            <div class="col-lg-4 col-md-6">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-syringe mr-2"></i>
                            Informe Insumos
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Reporte de insumos médicos dispensados con categorización y valores asociados.
                        </p>
                        <ul class="fa-ul text-sm">
                            <li><span class="fa-li"><i class="fas fa-info-circle text-warning"></i></span> En desarrollo</li>
                            <li><span class="fa-li"><i class="fas fa-info-circle text-warning"></i></span> Próximamente</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-warning btn-block" disabled>
                            <i class="fas fa-lock mr-2"></i>
                            Próximamente
                        </button>
                    </div>
                </div>
            </div>

            <!-- Dispensación Múltiple -->
            <div class="col-lg-4 col-md-6">
                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-layer-group mr-2"></i>
                            Dispensación Múltiple
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Análisis de pacientes con múltiples dispensaciones en el periodo seleccionado.
                        </p>
                        <ul class="fa-ul text-sm">
                            <li><span class="fa-li"><i class="fas fa-info-circle text-danger"></i></span> En desarrollo</li>
                            <li><span class="fa-li"><i class="fas fa-info-circle text-danger"></i></span> Próximamente</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-danger btn-block" disabled>
                            <i class="fas fa-lock mr-2"></i>
                            Próximamente
                        </button>
                    </div>
                </div>
            </div>

            <!-- Estadísticas por Sede -->
            <div class="col-lg-4 col-md-6">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Resumen por Sede
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            Estadísticas agrupadas por sede con totales y promedios.
                        </p>
                        <ul class="fa-ul text-sm">
                            <li><span class="fa-li"><i class="fas fa-check text-secondary"></i></span> Por ciudad</li>
                            <li><span class="fa-li"><i class="fas fa-check text-secondary"></i></span> Totales y promedios</li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-secondary btn-block" onclick="cargarResumenSede()">
                            <i class="fas fa-play mr-2"></i>
                            Generar Informe
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- Área de Resultados -->
        <div id="area-resultados" style="display: none;">

            <!-- Resultado Informe Dispensación -->
            <div id="resultado-dispensacion" class="resultado-informe" style="display: none;">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-2"></i>
                            Resultados - Informe Dispensación
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" onclick="cerrarResultado('resultado-dispensacion')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card shadow-sm border-primary">
                                    <div class="card-header bg-primary text-white text-center">
                                        <h5 class="mb-0">Pendientes por Revisar</h5>
                                    </div>
                                    <div class="card-body" id="detalle_informe_disp"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card shadow-sm border-success">
                                    <div class="card-header bg-success text-white text-center">
                                        <h5 class="mb-0">Revisadas</h5>
                                    </div>
                                    <div class="card-body" id="detalle_informe1_disp"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card shadow-sm border-danger">
                                    <div class="card-header bg-danger text-white text-center">
                                        <h5 class="mb-0">Anulados</h5>
                                    </div>
                                    <div class="card-body" id="detalle_informe2_disp"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resultado Informe ForGif -->
            <div id="resultado-forgif" class="resultado-informe" style="display: none;">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-excel mr-2"></i>
                            Resultados - Informe FOR_GIF_003
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool btn-sm" onclick="exportarForgif('excel')">
                                <i class="fas fa-file-excel mr-1"></i> Excel
                            </button>
                            <button type="button" class="btn btn-tool" onclick="cerrarResultado('resultado-forgif')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="tablaForgif" class="table table-striped table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>NIT del prestador</th>
                                    <th>Prestador</th>
                                    <th>Código Genérico EPS</th>
                                    <th>Código Expediente</th>
                                    <th>Código comercial</th>
                                    <th>Nombre genérico</th>
                                    <th>Nombre comercial</th>
                                    <th>Unidad mínima</th>
                                    <th>Valor Unitario</th>
                                    <th>CUM</th>
                                    <th>Modalidad</th>
                                    <th>Registro Sanitario</th>
                                    <th>OPCIÓN</th>
                                    <th>PBS/NO PBS</th>
                                    <th>REGULADO</th>
                                    <th>Categoría</th>
                                    <th>Forma Farmacéutica</th>
                                    <th>Tarifa Tope</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Datos dinámicos -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resultado Resumen por Sede -->
            <div id="resultado-sede" class="resultado-informe" style="display: none;">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Resultados - Resumen por Sede
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" onclick="cerrarResultado('resultado-sede')">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="tabla-resumen-sede" class="table table-striped table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Sede</th>
                                    <th>Total Dispensados</th>
                                    <th>Valor Total</th>
                                    <th>Promedio</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-resumen-sede">
                                <!-- Datos dinámicos -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>
@endsection

@section("scriptsPlugins")
<script>
// Asegurarse de que el script se ejecuta después de cargar jQuery y DOM
$(document).ready(function() {
    console.log('Script de informes cargado correctamente');
});

// Validar que las fechas no estén vacías
window.validarFechas = function() {
    const fechaini = $('#fechaini').val();
    const fechafin = $('#fechafin').val();

    if (!fechaini || !fechafin) {
        Swal.fire({
            icon: 'warning',
            title: 'Fechas requeridas',
            text: 'Por favor seleccione fecha inicial y final',
            confirmButtonColor: '#3085d6'
        });
        return false;
    }

    if (fechaini > fechafin) {
        Swal.fire({
            icon: 'error',
            title: 'Error en fechas',
            text: 'La fecha inicial no puede ser mayor que la fecha final',
            confirmButtonColor: '#d33'
        });
        return false;
    }

    return true;
}

// Función para cerrar resultado
window.cerrarResultado = function(idResultado) {
    $('#' + idResultado).fadeOut();
    if ($('.resultado-informe:visible').length === 0) {
        $('#area-resultados').fadeOut();
    }
}

// Cargar Informe Dispensación - CORREGIDO (se añadió la llave de cierre faltante)
window.cargarInformeDispensacion = function() {
    if (!validarFechas()) return;

    const fechaini = $('#fechaini').val();
    const fechafin = $('#fechafin').val();
    const contrato = $('#contrato').val();

    Swal.fire({
        title: 'Generando informe...',
        html: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '{{ route("medcol6.informedis") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            fechaini: fechaini,
            fechafin: fechafin,
            contrato: contrato
        },
        success: function(response) {
            Swal.close();

            // Validar estructura de respuesta
            if (response && response.dispensado !== undefined && response.revisado !== undefined && response.anulados !== undefined) {
                // Mostrar resultado
                $('#detalle_informe_disp').html(response.dispensado);
                $('#detalle_informe1_disp').html(response.revisado);
                $('#detalle_informe2_disp').html(response.anulados);

                $('#area-resultados').fadeIn();
                $('#resultado-dispensacion').fadeIn();

                // Scroll al resultado
                $('html, body').animate({
                    scrollTop: $("#resultado-dispensacion").offset().top - 100
                }, 500);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en datos',
                    text: 'La respuesta del servidor no tiene la estructura esperada',
                    confirmButtonColor: '#d33'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo generar el informe: ' + (xhr.responseJSON?.message || 'Error de conexión'),
                confirmButtonColor: '#d33'
            });
        }
    });
} // ← ESTA ERA LA LLAVE FALTANTE

// Cargar Informe ForGif
window.cargarInformeForgif = function() {
    if (!validarFechas()) return;

    const fechaini = $('#fechaini').val();
    const fechafin = $('#fechafin').val();
    const contrato = $('#contrato').val();

    Swal.fire({
        title: 'Generando informe FOR_GIF_003...',
        html: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '{{ route("medcol6.forgif") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            fechaini: fechaini,
            fechafin: fechafin,
            contrato: contrato
        },
        success: function(response) {
            Swal.close();

            // Limpiar tabla
            $('#tablaForgif tbody').empty();

            // Llenar tabla
            if (response && response.length > 0) {
                response.forEach(function(item) {
                    const row = `<tr>
                        <td>${item.nit_prestador || ''}</td>
                        <td>${item.prestador || ''}</td>
                        <td>${item.codigo_generico || ''}</td>
                        <td>${item.codigo_expediente || ''}</td>
                        <td>${item.codigo_comercial || ''}</td>
                        <td>${item.nombre_generico || ''}</td>
                        <td>${item.nombre_comercial || ''}</td>
                        <td>${item.unidad_minima || ''}</td>
                        <td>${item.valor_unitario || ''}</td>
                        <td>${item.cum || ''}</td>
                        <td>${item.modalidad || ''}</td>
                        <td>${item.registro_sanitario || ''}</td>
                        <td>${item.opcion || ''}</td>
                        <td>${item.pbs_nopbs || ''}</td>
                        <td>${item.regulado || ''}</td>
                        <td>${item.categoria || ''}</td>
                        <td>${item.forma_farmaceutica || ''}</td>
                        <td>${item.tarifa_tope || ''}</td>
                    </tr>`;
                    $('#tablaForgif tbody').append(row);
                });

                $('#area-resultados').fadeIn();
                $('#resultado-forgif').fadeIn();

                // Scroll al resultado
                $('html, body').animate({
                    scrollTop: $("#resultado-forgif").offset().top - 100
                }, 500);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin datos',
                    text: 'No se encontraron registros para el periodo seleccionado',
                    confirmButtonColor: '#3085d6'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo generar el informe FOR_GIF: ' + (xhr.responseJSON?.message || 'Error de conexión'),
                confirmButtonColor: '#d33'
            });
        }
    });
}

// Cargar Resumen por Sede
window.cargarResumenSede = function() {
    if (!validarFechas()) return;

    const fechaini = $('#fechaini').val();
    const fechafin = $('#fechafin').val();

    Swal.fire({
        title: 'Generando resumen por sede...',
        html: 'Por favor espere',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '{{ route("medcol6.gestionsdis") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            fechaini: fechaini,
            fechafin: fechafin
        },
        success: function(response) {
            Swal.close();

            if (response) {
                $('#tbody-resumen-sede').html(response);

                $('#area-resultados').fadeIn();
                $('#resultado-sede').fadeIn();

                // Scroll al resultado
                $('html, body').animate({
                    scrollTop: $("#resultado-sede").offset().top - 100
                }, 500);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin datos',
                    text: 'No se encontraron registros para el periodo seleccionado',
                    confirmButtonColor: '#3085d6'
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo generar el resumen por sede: ' + (xhr.responseJSON?.message || 'Error de conexión'),
                confirmButtonColor: '#d33'
            });
        }
    });
}

// Exportar ForGif a Excel
window.exportarForgif = function(formato) {
    Swal.fire({
        icon: 'info',
        title: 'Exportación',
        text: 'Esta funcionalidad estará disponible próximamente',
        confirmButtonColor: '#3085d6'
    });
}

// Inicializar cuando el documento esté listo
$(document).ready(function() {
    // Inicializar Select2
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });

    // Verificar que las funciones estén en el scope global
    console.log('Funciones registradas en window:', {
        cargarInformeDispensacion: typeof window.cargarInformeDispensacion,
        cargarInformeForgif: typeof window.cargarInformeForgif,
        cargarResumenSede: typeof window.cargarResumenSede,
        exportarForgif: typeof window.exportarForgif,
        cerrarResultado: typeof window.cerrarResultado,
        validarFechas: typeof window.validarFechas
    });
});
</script>
@endsection