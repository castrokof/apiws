# Sistema de Gestión de Medicamentos Pendientes

Sistema web desarrollado en Laravel 7.x para la gestión de dispensación de medicamentos y seguimiento de pendientes farmacéuticos a través de múltiples entidades (Medcol2, Medcol3, Medcol5, Medcol6, Medcold).

## Características Principales

- **Gestión Multi-Entidad**: Soporte para múltiples entidades farmacéuticas
- **Seguimiento de Prescripciones**: Control completo del flujo de medicamentos
- **Métricas de Entrega en Tiempo Real**: Sistema de priorización basado en límites de tiempo
- **Integración API**: Sincronización con sistemas externos
- **Reportes Avanzados**: Generación de informes detallados
- **Gestión de Inventario**: Control de saldos y desabastecimientos

## 📋 Changelog

### v2.1 (Enero 2025) - Optimización de Entregas Consolidadas

**🚀 Nuevas Funcionalidades:**
- Sistema de sugerencias predictivas refactorizado para múltiples medicamentos
- Interfaz de usuario mejorada con dropdown interactivo de medicamentos
- Sistema de contacto consolidado para entregas agrupadas
- Métricas avanzadas por paciente con ventanas de oportunidad

**🔧 Mejoras Técnicas:**
- Query optimizada con `GROUP BY` para agrupación de pacientes
- Funciones JavaScript auxiliares para cálculos dinámicos
- Sistema de badges y colores dinámicos basado en prioridad
- Interfaz responsiva con scroll automático

**🐛 Correcciones:**
- JavaScript syntax errors en funciones `contactPatient` y `viewDetails`
- Comillas faltantes en eventos `onclick` corregidas
- Eliminación completa de errores de consola

---

## 🚀 Funcionalidades Recientes

### 🆕 Últimas Actualizaciones (v2.1)

#### 🔧 Refactorización del Sistema de Sugerencias Predictivas

##### ✨ Nuevo Enfoque: Pacientes con Múltiples Medicamentos
El sistema `getPredictiveSuggestions()` ha sido completamente refactorizado para enfocarse en la **consolidación de entregas**:

- **Priorización Inteligente**: Identifica pacientes con 2 o más medicamentos pendientes dentro de la ventana de oportunidad (0-48 horas)
- **Optimización de Rutas**: Reduce múltiples entregas individuales a una sola entrega consolidada
- **Eficiencia Operativa**: Minimiza costos de entrega y mejora la experiencia del paciente

##### 🎯 Criterios de Priorización Automática
| Prioridad | Criterios | Acción Recomendada | Plazo |
|-----------|-----------|-------------------|--------|
| **ALTA** | 4+ medicamentos O 40+ horas promedio | Contacto inmediato para entrega consolidada | INMEDIATO |
| **MEDIA-ALTA** | 3+ medicamentos O 30+ horas promedio | Planificación prioritaria de entrega agrupada | 12 HORAS |
| **MEDIA** | 2+ medicamentos | Agrupación para eficiencia operativa | 24 HORAS |

##### 📊 Métricas Avanzadas por Paciente
```json
{
  "documento": "123456789",
  "paciente": "Juan Pérez García",
  "total_medicamentos": 3,
  "promedio_horas_transcurridas": 28.5,
  "fecha_mas_antigua": "2024-01-15 08:30:00",
  "fecha_mas_reciente": "2024-01-16 14:20:00",
  "ventaja_consolidacion": "Reducir de 3 entregas individuales a 1 entrega consolidada"
}
```

#### 🎨 Nueva Interfaz de Usuario Mejorada

##### 📋 Dropdown Interactivo de Medicamentos
- **Reemplazo del Campo Simple**: Se eliminó el campo estático "Medicamento" 
- **Vista Detallada**: Dropdown expandible que muestra todos los medicamentos del paciente
- **Información Completa**: Cada medicamento incluye días pendientes y estado visual
- **Diseño Responsivo**: Interfaz adaptable con scroll automático para listas largas

##### 🎛️ Características del Dropdown
```javascript
// Estructura del nuevo dropdown
const dropdownFeatures = {
    header: "Total de medicamentos con contador",
    items: [
        {
            medicamento: "Nombre del medicamento",
            dias_pendientes: "Calculado dinámicamente",
            badge_color: "Verde/Amarillo/Rojo según criticidad",
            informacion_adicional: "Códigos y cantidades (próximamente)"
        }
    ],
    footer: "Rango de días y resumen estadístico"
};
```

##### 📞 Sistema de Contacto Consolidado
- **Función `contactPatientMultiple()`**: Manejo especializado para múltiples medicamentos
- **Guión Optimizado**: Script específico para entregas consolidadas
- **Beneficios Destacados**: Lista automática de ventajas para el paciente
- **Interfaz Profesional**: Modal mejorado con información completa

##### 🔍 Funciones Auxiliares Nuevas
- **`calculateDaysBetween()`**: Cálculo preciso de días transcurridos
- **`viewPatientDetails()`**: Acceso rápido a detalles del paciente
- **`getMedicationDetailsDropdown()`**: Generación dinámica de listas de medicamentos

#### 🛠️ Mejoras Técnicas Implementadas

##### Backend (SmartPendiController.php)
```php
// Query optimizada con agrupación por paciente
$query = PendienteApiMedcol6::query()
    ->select([
        'documento', 'nombre1', 'nombre2', 'apellido1', 'apellido2',
        'telefres', 'municipio',
        DB::raw('COUNT(*) as total_medicamentos'),
        DB::raw('GROUP_CONCAT(nombre SEPARATOR " | ") as medicamentos_list'),
        DB::raw('MIN(fecha_factura) as fecha_mas_antigua'),
        DB::raw('MAX(fecha_factura) as fecha_mas_reciente'),
        DB::raw('AVG(TIMESTAMPDIFF(HOUR, fecha_factura, NOW())) as promedio_horas_transcurridas')
    ])
    ->groupBy(['documento', 'nombre1', 'nombre2', 'apellido1', 'apellido2'])
    ->having('total_medicamentos', '>=', 2);
```

##### Frontend (dashboard.blade.php)
- **Renderizado Dinámico**: Generación de tarjetas adaptativas según prioridad
- **Gestión de Estados**: Colores y badges dinámicos basados en métricas
- **Interactividad Mejorada**: Eventos y handlers optimizados
- **Accesibilidad**: Atributos ARIA y navegación por teclado

#### 📈 Beneficios Operativos Documentados

##### 💰 Reducción de Costos
- **Optimización de Rutas**: Menos viajes, menor consumo de combustible
- **Eficiencia de Personal**: Un delivery por múltiples medicamentos
- **Recursos Administrativos**: Menos coordinación de entregas individuales

##### 😊 Mejora en Experiencia del Cliente
- **Comodidad**: Una sola visita para todos los medicamentos
- **Confiabilidad**: Mejor cumplimiento de promesas de entrega
- **Comunicación**: Contacto consolidado y profesional

##### 📊 Métricas de Rendimiento
- **Tiempo de Entrega**: Cumplimiento mejorado de la ventana 0-48h
- **Satisfacción**: Reducción de molestias por múltiples visitas
- **Eficiencia**: Métricas de consolidación automáticas

#### 🐛 Correcciones de Errores Críticos

##### JavaScript Syntax Errors (Resuelto)
**Problema**: Error `Uncaught SyntaxError: Invalid or unexpected token` en funciones `contactPatient` y `viewDetails`

**Ubicación**: `resources/views/smart-pendi/dashboard.blade.php`
- Línea 277: Falta de comilla de cierre en función `contactPatient()`
- Línea 297: Falta de comilla de cierre en función `viewDetails()`

**Solución Aplicada**:
```javascript
// ANTES (Error de sintaxis)
onclick="contactPatient('id', 'name', 'phone', 'medication')"  // ✗ Error
onclick="viewDetails('id')"                                    // ✗ Error

// DESPUÉS (Corregido)
onclick="contactPatient('id', 'name', 'phone', 'medication')"  // ✓ Correcto
onclick="viewDetails('id')"                                    // ✓ Correcto
```

**Impacto**: 
- ✅ Funciones JavaScript ejecutándose correctamente
- ✅ Botones de contacto y detalles funcionales
- ✅ Eliminación completa de errores de consola

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

##### 🔄 Endpoints Actualizados (v2.1)

```bash
# Dashboard principal de Smart Pendi
GET /smart/pendi

# Análisis de pendientes (0-48h) - DataTable con server-side processing
GET /smart/pendi/analysis?start=0&length=25&search[value]=paciente&order[0][column]=4&order[0][dir]=desc

# Estadísticas en tiempo real (con caché de 5 minutos)
GET /smart/pendi/statistics

# NUEVO: Sugerencias predictivas con enfoque en múltiples medicamentos
GET /smart/pendi/suggestions
# Respuesta mejorada:
{
  "success": true,
  "suggestions": [
    {
      "pendiente_ids": [123, 124, 125],
      "documento": "12345678",
      "paciente": "Juan Pérez",
      "total_medicamentos": 3,
      "medicamentos": "Ibuprofeno | Acetaminofén | Loratadina",
      "prioridad": "ALTA",
      "promedio_horas_transcurridas": 35.2,
      "ventaja_consolidacion": "Reducir de 3 entregas individuales a 1 entrega consolidada"
    }
  ],
  "enfoque": "Pacientes con múltiples medicamentos pendientes (2+) en ventana de oportunidad 0-48h",
  "beneficios": ["Optimización de rutas de entrega", "Reducción de costos operativos"]
}

# Resumen estadístico
GET /smart/pendi/summary
```

##### 📊 Parámetros de Query Mejorados

```bash
# Búsqueda avanzada en análisis
GET /smart/pendi/analysis
  ?start=0                          # Offset para paginación
  &length=25                        # Registros por página
  &search[value]=juan               # Búsqueda global
  &order[0][column]=4               # Columna a ordenar (4 = horas transcurridas)
  &order[0][dir]=desc              # Dirección de ordenamiento
  &draw=1                          # Contador de request (DataTables)

# Filtros específicos (futuras implementaciones)
GET /smart/pendi/analysis
  ?municipio=CALI                  # Filtro por municipio
  &prioridad=ALTA                  # Filtro por prioridad
  &min_medicamentos=3              # Mínimo de medicamentos por paciente
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
