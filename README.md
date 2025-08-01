# Sistema de Gestión de Medicamentos Pendientes

Sistema web desarrollado en Laravel 7.x para la gestión de dispensación de medicamentos y seguimiento de pendientes farmacéuticos a través de múltiples entidades (Medcol2, Medcol3, Medcol5, Medcol6, Medcold).

## Características Principales

- **Gestión Multi-Entidad**: Soporte para múltiples entidades farmacéuticas
- **Seguimiento de Prescripciones**: Control completo del flujo de medicamentos
- **Métricas de Entrega en Tiempo Real**: Sistema de priorización basado en límites de tiempo
- **Integración API**: Sincronización con sistemas externos
- **Reportes Avanzados**: Generación de informes detallados
- **Gestión de Inventario**: Control de saldos y desabastecimientos

## 🚀 Funcionalidades Recientes

### 🧠 Smart Pendi - Sistema de Análisis Predictivo

#### Descripción
Módulo inteligente de análisis predictivo que se enfoca en la ventana de oportunidad de 0-48 horas para optimizar la entrega oportuna de medicamentos pendientes.

#### ✨ Características Principales

##### 📊 Análisis Enfocado (0-48 Horas)
- **Ventana de Oportunidad**: Filtrado inteligente de pendientes dentro del rango crítico de 0-48 horas
- **Prevención Proactiva**: Identificación temprana de medicamentos antes de superar el límite de 48 horas
- **Optimización de Recursos**: Enfoque en entregas que aún pueden cumplir con los tiempos establecidos

##### 🔍 DataTable con Procesamiento del Servidor
- **Rendimiento Optimizado**: Manejo eficiente de más de 100,000 registros sin saturar el navegador
- **Paginación Configurable**: 25 registros por defecto con opciones de 10, 25, 50, 100 o todos
- **Búsqueda en Tiempo Real**: Filtrado instantáneo por paciente, documento, medicamento, municipio
- **Ordenamiento Dinámico**: Columnas ordenables con prioridad por horas transcurridas
- **Exportación**: Botones integrados para exportar a Excel y PDF

##### 📈 Métricas Actualizadas
| Métrica | Descripción | Color |
|---------|-------------|-------|
| **Total Dentro 48h** | Pendientes en ventana de oportunidad | Azul |
| **Críticos 24-48h** | Pendientes entre 24 y 48 horas | Amarillo |
| **Próximos a Vencer** | Pendientes entre 40-48 horas | Rojo |
| **Nuevos -24h** | Pendientes recién creados | Verde |

##### 🎯 Interfaz Inteligente
- **Auto-actualización**: Métricas que se actualizan automáticamente cada 5 minutos
- **Visualización por Horas**: Precisión en horas en lugar de días para mejor control
- **Colores Dinámicos**: Sistema de badges con colores según prioridad
- **Responsivo**: Adaptable a dispositivos móviles y tablets

#### 🏗️ Arquitectura Técnica

##### Backend (SmartPendiController.php)
```php
// Rutas del módulo Smart Pendi
Route::get('/smart/pendi', 'SmartPendiController@index');
Route::get('/smart/pendi/analysis', 'SmartPendiController@getPendientesAnalysis');
Route::get('/smart/pendi/suggestions', 'SmartPendiController@getPredictiveSuggestions');
Route::get('/smart/pendi/statistics', 'SmartPendiController@getStatistics');
Route::get('/smart/pendi/summary', 'SmartPendiController@getSummary');
```

##### Características del Controlador
- **Filtrado Inteligente**: Query optimizada para ventana 0-48 horas
- **Procesamiento Servidor**: Paginación, búsqueda y ordenamiento del lado del servidor
- **Cache Inteligente**: Sistema de caché para mejorar rendimiento de estadísticas
- **Búsqueda Avanzada**: Filtrado por múltiples campos simultáneamente

##### Frontend (dashboard.blade.php)
```javascript
// DataTable con configuración avanzada
$('#pendientes-table').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 25,
    responsive: true,
    language: { url: 'Spanish.json' },
    buttons: ['excel', 'pdf', 'pageLength']
});
```

##### Librerías Integradas
- **DataTables 1.13.6**: Tablas interactivas avanzadas
- **Buttons Plugin**: Exportación Excel/PDF
- **Responsive Plugin**: Adaptabilidad móvil
- **Spanish Language**: Interfaz en español

#### 🔧 Mejoras de Rendimiento

##### Optimizaciones de Base de Datos
- **Query Optimization**: Consultas optimizadas con índices apropiados
- **Server-Side Processing**: Transferencia mínima de datos
- **Clonado Correcto**: Uso apropiado del operador `clone` en PHP
- **Cache Estratégico**: Almacenamiento en caché de estadísticas frecuentes

##### Optimizaciones de Frontend
- **Carga Asíncrona**: Librerías cargadas de CDN optimizado
- **Renderizado Eficiente**: Funciones render optimizadas para DataTable
- **Memoria Controlada**: Liberación apropiada de recursos
- **Auto-refresh Inteligente**: Actualización selectiva de componentes

### 📊 Métricas de Entrega (Funcionalidad Existente)

Sistema automatizado de cálculo y visualización de métricas de entrega que permite priorizar pendientes según el límite crítico de 48 horas desde la fecha de facturación.

#### Estados de Prioridad
| Estado | Tiempo | Color | Descripción |
|--------|--------|-------|-------------|
| 🟢 **EN TIEMPO** | ≤ 24 horas | Verde | Entrega en tiempo óptimo |
| 🟡 **PRIORIDAD** | 25-48 horas | Amarillo | Requiere atención prioritaria |
| 🔴 **CRÍTICO** | 49-72 horas | Rojo | Estado crítico - Límite superado |
| 🚨 **URGENTE** | > 72 horas | Rojo + Borde | Requiere acción inmediata |

#### Componentes
```
app/Helpers/DeliveryMetricsHelper.php
├── calcularDiasTranscurridos()
├── calcularFechaEstimadaEntrega()
├── calcularEstadoPrioridad()
└── obtenerTodasLasMetricas()
```

### 🎯 Acceso y Uso

#### Acceso al Módulo Smart Pendi
Para acceder al sistema de análisis predictivo:

1. **URL Directa**: `http://tu-dominio/smart/pendi`
2. **Navegación**: Panel principal → Smart Pendi
3. **Permisos**: Requiere autenticación y middleware `verified` + `verifyuser`

#### Flujo de Trabajo Recomendado

1. **📊 Ejecutar Análisis**: Clic en "Ejecutar Análisis" para cargar pendientes dentro de 0-48h
2. **🔍 Filtrar Datos**: Usar la búsqueda del DataTable para encontrar casos específicos
3. **📋 Revisar Prioridades**: Ordenar por "Horas Transcurridas" para ver casos más urgentes
4. **💡 Ver Sugerencias**: Clic en "Ver Sugerencias" para recomendaciones automatizadas
5. **📞 Contactar Pacientes**: Usar los botones de teléfono integrados
6. **📄 Exportar**: Generar reportes en Excel o PDF según necesidad

#### Ejemplo de Implementación

```php
// En tu controlador
use App\Helpers\DeliveryMetricsHelper;

public function mostrarPendiente($id) {
    $pendiente = PendienteApiMedcol6::find($id);
    
    // Calcular métricas automáticamente
    $metricas = DeliveryMetricsHelper::obtenerTodasLasMetricas(
        $pendiente->fecha_factura
    );
    
    return view('pendiente.detalle', [
        'pendiente' => $pendiente,
        'horas_transcurridas' => $metricas['horas_transcurridas'],
        'estado_prioridad' => $metricas['estado_prioridad']['estado']
    ]);
}
```

#### API Endpoints

```bash
# Obtener análisis con paginación
GET /smart/pendi/analysis?start=0&length=25&search[value]=paciente

# Estadísticas del dashboard
GET /smart/pendi/statistics

# Sugerencias predictivas
GET /smart/pendi/suggestions

# Resumen de pendientes
GET /smart/pendi/summary
```

## Instalación y Configuración

### Requisitos
- PHP 7.2.5+ o 8.0+
- Laravel 7.x
- XAMPP (Windows)
- Composer
- NPM

### Comandos de Desarrollo

```bash
# Instalación de dependencias
composer install
npm install

# Compilación de assets
npm run dev              # Desarrollo
npm run watch            # Vigilancia de cambios
npm run production       # Producción

# Base de datos
php artisan migrate
php artisan db:seed

# Servidor de desarrollo
php artisan serve
```

### Comandos de Testing

```bash
# Ejecutar pruebas
vendor/bin/phpunit

# Regenerar autoloader (después de agregar helpers)
composer dump-autoload
```

## Arquitectura del Sistema

### Estructura Multi-Entidad
- **Medcol2**: Gestión base de medicamentos
- **Medcol3**: Entidad secundaria
- **Medcol5**: Implementación EMCALI
- **Medcol6**: Entidades SOS y JAMUNDI
- **Medcold**: Gestión de medicamentos para dolor
- **MedcolCli**: Vistas específicas de cliente

### Flujo de Trabajo
```
Direccionado → Programado → Dispensado → Entregado → Facturado
```

### Modelos Principales
- `PendienteApi[Entity]`: Pendientes por entidad
- `DispensadoApi[Entity]`: Medicamentos dispensados
- `EntregadosApi[Entity]`: Entregas realizadas
- `ObservacionesApi[Entity]`: Observaciones del proceso

## Integraciones

- **SOS Web Services**: Servicios SOAP y REST
- **APIs Externas**: Sincronización con sistemas farmacéuticos
- **Excel Import/Export**: Maatwebsite Excel
- **Sistema Hercules**: Autenticación externa

## Tecnologías Utilizadas

- **Backend**: Laravel 7.x, PHP 8.0
- **Frontend**: AdminLTE, Bootstrap 4, jQuery
- **Base de Datos**: MySQL con Eloquent ORM
- **APIs**: Guzzle HTTP Client
- **Reportes**: DataTables, Excel export

## Contribución

Para contribuir al proyecto:

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a tu rama (`git push origin feature/nueva-funcionalidad`)
5. Crea un Pull Request

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.
