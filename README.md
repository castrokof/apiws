# Sistema de Gestión de Medicamentos Pendientes

Sistema web desarrollado en Laravel 7.x para la gestión de dispensación de medicamentos y seguimiento de pendientes farmacéuticos a través de múltiples entidades (Medcol2, Medcol3, Medcol5, Medcol6, Medcold).

## Características Principales

- **Gestión Multi-Entidad**: Soporte para múltiples entidades farmacéuticas
- **Seguimiento de Prescripciones**: Control completo del flujo de medicamentos
- **Métricas de Entrega en Tiempo Real**: Sistema de priorización basado en límites de tiempo
- **Integración API**: Sincronización con sistemas externos
- **Reportes Avanzados**: Generación de informes detallados
- **Gestión de Inventario**: Control de saldos y desabastecimientos

## 🚀 Nueva Funcionalidad: Métricas de Entrega

### Descripción
Sistema automatizado de cálculo y visualización de métricas de entrega que permite priorizar pendientes según el límite crítico de 48 horas desde la fecha de facturación.

### Características Implementadas

#### 📊 Cálculos Automáticos
- **Días Transcurridos**: Diferencia en días entre fecha de factura y fecha actual
- **Fecha Estimada de Entrega**: Cálculo de 48 horas posteriores a la fecha de factura
- **Tiempo Restante**: Cuenta regresiva hasta el límite de 48 horas
- **Estado de Prioridad**: Clasificación automática basada en tiempo transcurrido

#### 🚨 Estados de Prioridad
| Estado | Tiempo | Color | Descripción |
|--------|--------|-------|-------------|
| 🟢 **EN TIEMPO** | ≤ 24 horas | Verde | Entrega en tiempo óptimo |
| 🟡 **PRIORIDAD** | 25-48 horas | Amarillo | Requiere atención prioritaria |
| 🔴 **CRÍTICO** | 49-72 horas | Rojo | Estado crítico - Límite superado |
| 🚨 **URGENTE** | > 72 horas | Rojo + Borde | Requiere acción inmediata |

#### ⚡ Funcionalidades Avanzadas
- **Actualización en Tiempo Real**: Recálculo automático cada minuto
- **Interfaz Visual Intuitiva**: Campos con colores dinámicos y emojis
- **Formato Optimizado**: Compatibilidad con inputs HTML5 datetime-local
- **Función Global**: `window.recalcularMetricasEntrega()` para integración

### Archivos Modificados

#### Componentes Backend
```
app/Helpers/DeliveryMetricsHelper.php (NUEVO)
├── calcularDiasTranscurridos()
├── calcularFechaEstimadaEntrega()
├── calcularEstadoPrioridad()
└── obtenerTodasLasMetricas()
```

#### Componentes Frontend
```
resources/views/menu/Medcol6/form/form.blade.php
├── Sección: "Información del Medicamento - Métricas de Entrega"
├── Campos: dias_transcurridos, fecha_estimada_entrega, 
│           horas_restantes, estado_prioridad
└── JavaScript: calcularMetricasEntrega() con auto-actualización
```

### Ejemplo de Uso

```php
use App\Helpers\DeliveryMetricsHelper;

// Obtener todas las métricas para una fecha
$metricas = DeliveryMetricsHelper::obtenerTodasLasMetricas('2025-07-29');

echo $metricas['dias_transcurridos']; // 2
echo $metricas['estado_prioridad']['estado']; // CRITICO
echo $metricas['fecha_estimada_entrega']; // 2025-07-31 00:00
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
