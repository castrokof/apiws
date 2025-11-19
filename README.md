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

### v2.8 (Noviembre 2025) - Actualización Masiva de Pendientes Entregados desde Excel

**🚀 Nuevas Funcionalidades:**
- **Script Automatizado de Actualización Masiva**: Herramienta Python para generar consultas SQL UPDATE desde archivos Excel
- **Procesamiento de 2,450+ Registros**: Capacidad de actualizar miles de registros en una sola consulta optimizada
- **Extracción Inteligente de Datos**: Parser automático que extrae información de múltiples columnas Excel
- **Manejo de Fechas Excel**: Conversión automática de formatos numéricos de Excel a fechas MySQL
- **Validación de Integridad**: Sistema de validación que garantiza coincidencia correcta usando concatenación de campos

**🔧 Arquitectura Implementada:**
- **Scripts de Procesamiento**:
  - `generate_update_query.py`: Script principal que genera la consulta SQL desde Excel
  - `read_excel_sheets.py`: Lector optimizado para archivos Excel grandes con múltiples hojas
- **Archivos Generados**:
  - `update_pendientes_entregados.sql`: Consulta UPDATE optimizada (1.1 MB, 2,450 registros)
  - `RESUMEN_CONSULTA_SQL.md`: Documentación completa con ejemplos y guía de uso
  - `verificar_antes_de_actualizar.sql`: Script de verificación pre-ejecución
  - `verificar_despues_de_actualizar.sql`: Script de verificación post-ejecución
- **Formato de Entrada**: Excel con hoja "Hoja1" conteniendo:
  - `unicos`: Campo de validación (documento+factura+codigo)
  - `Dispensación`: Formato CDIO66615 (letras + números)
  - `Fecha entrega`: Fecha en múltiples formatos soportados

**🎯 Campos Actualizados:**
La consulta actualiza 6 campos de la tabla `pendiente_api_medcol6`:
1. **estado**: Se establece en `'ENTREGADO'`
2. **usuario**: Se establece en `'SYSTEM'`
3. **fecha_entrega**: Extraída del campo "Fecha entrega" del Excel
4. **doc_entrega**: Letras del campo "Dispensación" (ej: CDIO)
5. **factura_entrega**: Números del campo "Dispensación" (ej: 66615)
6. **updated_at**: Se establece en `NOW()`

**🔍 Sistema de Validación:**
```sql
-- WHERE clause con concatenación de campos
WHERE CONCAT(documento, factura, codigo) IN (
    'MPE30187M000447-01',
    'MPE30310M000891-03',
    ...
)
```
- **Campo Excel "unicos"**: Se compara con `CONCAT(documento, factura, codigo)` de la tabla
- **Garantía de Precisión**: Solo se actualizan registros que coinciden exactamente

**📊 Extracción Inteligente:**
```python
# Del campo "Dispensación" (formato: CDIO66615)
regex = r'^([A-Za-z]+)(\d+)$'
# Resultado:
#   doc_entrega = 'CDIO'        (letras)
#   factura_entrega = '66615'    (números)
```

**🗓️ Manejo Avanzado de Fechas:**
El script maneja 3 formatos de fecha automáticamente:
1. **Formato datetime**: `2025-10-15 00:00:00` → Sin conversión
2. **Formato fecha**: `2025-10-15` → Se añade `00:00:00`
3. **Formato numérico Excel**: `45959` → Conversión desde época 1899-12-30

**💪 Optimizaciones Técnicas:**
- **CASE Statements**: Uso de CASE para actualización condicional eficiente
- **Single Query**: Todos los registros en una sola consulta (evita 2,450 UPDATEs individuales)
- **Tamaño Optimizado**: 1.1 MB para 2,450 registros con validación completa
- **Memory Efficient**: Script Python con límite de memoria de 2048M

**🛡️ Seguridad y Validación:**
```bash
# Flujo de trabajo recomendado
1. Backup de tabla:
   mysqldump -u usuario -p database pendiente_api_medcol6 > backup.sql

2. Verificación pre-ejecución:
   mysql -u usuario -p database < verificar_antes_de_actualizar.sql

3. Ajustar max_allowed_packet:
   SET GLOBAL max_allowed_packet = 16777216;

4. Ejecutar UPDATE:
   mysql -u usuario -p database < update_pendientes_entregados.sql

5. Verificación post-ejecución:
   mysql -u usuario -p database < verificar_despues_de_actualizar.sql
```

**📈 Beneficios Operativos:**
- **Velocidad**: Actualización de 2,450 registros en segundos vs. minutos con updates individuales
- **Confiabilidad**: Validación estricta previene actualizaciones incorrectas
- **Trazabilidad**: Scripts de verificación permiten auditar antes y después
- **Reutilizable**: Script Python puede procesar cualquier archivo Excel con estructura similar
- **Documentación Completa**: README con ejemplos, casos de uso y troubleshooting

**🐛 Características de Robustez:**
- **Manejo de Errores**: Validación de datos antes de generar SQL
- **Logs Detallados**: Registro de registros procesados vs. omitidos
- **Formato Consistente**: Escapado correcto de comillas y caracteres especiales
- **Fechas Validadas**: Detección y conversión de múltiples formatos de fecha

**📚 Archivos de Documentación:**
- `RESUMEN_CONSULTA_SQL.md`: Guía completa con:
  - Estructura de la consulta generada
  - Mapeo de campos Excel → Base de datos
  - Ejemplos de uso con outputs esperados
  - Comandos de verificación y rollback
  - Troubleshooting para problemas comunes

**🔄 Casos de Uso:**
1. **Sincronización Masiva**: Actualizar estados desde sistemas externos vía Excel
2. **Migración de Datos**: Importar entregas históricas desde hojas de cálculo
3. **Corrección en Lote**: Actualizar registros con información corregida
4. **Integración Legacy**: Procesar datos de sistemas antiguos que exportan a Excel

---

### v2.7 (Octubre 2025) - Sistema de Roles y Permisos con Menú Moderno

**🚀 Nuevas Funcionalidades:**
- **Sistema RBAC Completo** con roles, permisos y gestión granular de acceso
- **Menú Lateral Moderno** basado en AdminLTE3 con permisos dinámicos
- **Gestión de Roles**: CRUD completo con asignación de permisos
- **Gestión de Permisos**: Interfaz organizada con filtros por módulo
- **Gestión de Usuarios**: Asignación de roles y permisos directos
- **6 Roles Predefinidos**: Super Admin, Administrador, Analista, Auxiliar, Droguería, Reportes
- **51 Permisos Granulares**: Organizados en 13 módulos del sistema

**🔧 Arquitectura Implementada:**
- **Backend**:
  - Modelos: `Role`, `Permission` con relaciones many-to-many
  - Middleware: `CheckRole`, `CheckPermission` para protección de rutas
  - Controladores: `RoleController`, `PermissionController`, `UserManagementController`
  - Migraciones: 5 tablas nuevas (roles, permissions, pivots)
  - Seeder: `RolesAndPermissionsSeeder` con datos iniciales completos
- **Frontend**:
  - Layout: `layouts/admin.blade.php` con integración de sidebar
  - Componente: `components/sidebar.blade.php` con menú dinámico por permisos
  - Vistas CRUD: roles/index, permissions/index, users/index, users/edit
  - CSS: `modern-sidebar.css` con animaciones y efectos modernos

**🎨 Características del Menú:**
- **Visibilidad Dinámica**: Elementos mostrados según permisos del usuario
- **Navegación Jerárquica**: Menús desplegables por módulo
- **Diseño Moderno**: Gradientes, sombras, animaciones smooth
- **Indicadores Visuales**: Badges de estado, iconografía temática
- **Responsive**: Adaptación móvil con sidebar colapsable

**📊 Módulos con Permisos:**
1. **Dashboard**: Visualización de métricas
2. **Análisis NT**: Análisis de datos
3. **Medcol2**: Pendientes y dispensados
4. **Medcol3-6, Medcold**: Gestión multi-entidad
5. **Inventario**: Compras, saldos, desabastecimientos
6. **Reportes**: Generación de informes
7. **Administración**: Usuarios, roles, permisos, configuración

**🔐 Sistema de Autenticación Mejorado:**
- Redirección automática a `/admin/home` después de login
- Validación de roles antes de acceso
- Mensajes de error informativos
- Protección contra eliminación de propios registros
- Super Admin no puede ser eliminado

**🐛 Correcciones Técnicas:**
- Namespace correcto de `User` model en Laravel 7.x
- Métodos helper en User: `hasRole()`, `hasPermission()`, `hasAnyPermission()`
- Blade directives personalizadas: `@role`, `@permission`
- Paginación en listados para mejor rendimiento
- Validación de datos en formularios con mensajes claros

**📈 Beneficios Operativos:**
- **Seguridad Mejorada**: Control granular de acceso por usuario
- **Gestión Flexible**: Asignación dinámica de permisos sin cambios de código
- **Auditoría**: Trazabilidad de quién puede hacer qué
- **Escalabilidad**: Fácil adición de nuevos roles/permisos según necesidad
- **UX Optimizada**: Menú limpio que solo muestra opciones relevantes

---

### v2.6 (Octubre 2025) - Dashboard Analytics con Análisis Diario Avanzado

**🚀 Nuevas Funcionalidades:**
- **Vista Unificada "Resumen General & Distribución"**: Unión de dos secciones en una sola para mejor experiencia visual
- **Análisis de Facturación Diaria**: Nueva sección con 2 gráficas complementarias
  - **Gráfica de Facturación Diaria** (línea verde): Seguimiento de ingresos día a día
  - **Gráfica de Pacientes Únicos Diarios** (barras púrpura): Volumen de atención sin duplicados
- **Tarjetas Estadísticas Diarias**: 3 cards horizontales con métricas clave
  - Día con mayor facturación (verde ⬆️)
  - Día con menor facturación (rojo ⬇️)
  - Día con más pacientes únicos atendidos (azul)
- **Cards de Meses con Mayor/Menor Facturación**: Identificación rápida de períodos clave
- **Paleta de Colores Unificada**: Top 5 Medicamentos ahora usa misma paleta que Valor por Contrato

**🔧 Mejoras Técnicas:**
- **Backend**:
  - Query optimizada para facturación diaria con `COUNT(DISTINCT historia)` para garantizar pacientes únicos
  - Cálculo de día de semana (`DAYOFWEEK`) para análisis temporal
  - Logs detallados en `laravel.log` con Top 7 días de mayor facturación
  - Campos adicionales: `total_registros`, `dia_semana` para debugging
- **Frontend**:
  - Validación exhaustiva de fechas con corrección de zona horaria (`fecha + 'T00:00:00'`)
  - Detección de fechas duplicadas y valores inválidos
  - Tooltips enriquecidos con día de la semana, pacientes, registros y comparación con promedio
  - Console logs detallados para debugging en cada paso del renderizado
- **Layout Mejorado**:
  - Grid 1:1 para gráficas diarias (facturación y pacientes lado a lado)
  - Grid 3 columnas para tarjetas estadísticas horizontales
  - Animaciones `fadeInUp` optimizadas para vista horizontal

**🎨 Mejoras Visuales:**
- **Gráfica Facturación Diaria**:
  - Línea verde con área rellena semitransparente
  - Labels con formato "DD MMM AA" (ej: "19 Oct 25")
  - Eje Y con formato monetario
  - Límite de 15 etiquetas en eje X para mejor legibilidad
- **Gráfica Pacientes Únicos**:
  - Barras verticales color púrpura (#6366f1)
  - Títulos de ejes visibles ("Número de Pacientes", "Fecha")
  - Formato "45 pac." en eje Y
  - Tooltip con promedio y diferencia (↑/↓)
- **Tarjetas Estadísticas**:
  - Diseño horizontal en 3 columnas
  - Día de la semana completo en español (ej: "domingo, 19 de octubre de 2025")
  - Información adicional: pacientes y registros totales
  - Iconos temáticos: `arrow-trend-up`, `arrow-trend-down`, `users`

**🐛 Correcciones:**
- **chartFacturacionDiaria**: Corrección de conversión de fechas que causaba días incorrectos
- **chartPacientes**: Eliminados intentos de renderizado de porcentajes en el canvas (causaba visual pobre)
- **Canvas rendering**: Uso de `requestAnimationFrame` en lugar de `setTimeout` para sincronización correcta
- **Selectores jQuery**: Cambio de selectores ineficientes a `.find().eq()` para evitar errores "Canvas no encontrado"

**📊 Análisis y Debugging:**
- **Logs de Backend** (Top 7 días con más facturación):
  ```json
  {
    "fecha": "2025-10-19",
    "dia_semana": "Domingo",
    "total_dia": "250,000.00",
    "pacientes": 45,
    "registros": 350
  }
  ```
- **Logs de Frontend** (Console):
  - Datos raw recibidos del servidor
  - Detección de fechas duplicadas
  - Detección de valores inválidos
  - Resumen de datos procesados (min, max, promedio)
- **Relación Registros/Pacientes**: Detecta posible duplicación cuando > 5:1

**📈 Beneficios Operativos:**
- **Análisis Dual**: Visualización simultánea de ingresos y volumen de atención
- **Detección de Anomalías**: Identificación de días con datos anormales (ej: domingos con alta facturación)
- **Comparación con Promedio**: Tooltip muestra si el día está por encima o debajo del promedio
- **Métricas Clave Visibles**: Cards con días extremos para toma de decisiones rápida
- **Debugging Facilitado**: Logs completos permiten identificar problemas de datos rápidamente

---

### v2.5 (Octubre 2024) - Dashboard Analytics Avanzado

**🚀 Nuevas Funcionalidades:**
- **Dashboard Analytics Optimizado** con arquitectura modular y carga asíncrona
- **Gráfica de Valor Facturado por Contrato** con visualización de barras coloridas
- **Sistema de Análisis por Distribución y Tendencias** con gráficas interactivas
- **Integración con Análisis NT** para cálculos precisos de valores facturados
- **Reportes Detallados** por medicamentos, pacientes y contratos

**📊 Módulos del Dashboard:**
1. **Resumen General & Distribución**: Estadísticas unificadas + distribución y tendencias
2. **Análisis de Pendientes**: Estados y valores pendientes por facturar
3. **Tendencias de Pendientes**: Evolución temporal con múltiples gráficas
4. **Reportes**: Tablas interactivas con exportación a Excel/PDF

**🔧 Mejoras Técnicas:**
- Nueva ruta: `/dashboard/valor-por-contrato` con endpoint optimizado
- Sistema de caché de 30 minutos para mejorar rendimiento
- Validación de datos y manejo de errores mejorado en todas las gráficas
- Indicadores de carga y mensajes informativos cuando no hay datos
- Console logs para debugging y seguimiento de problemas

**🎨 Mejoras Visuales:**
- Gráficas con Chart.js: barras, líneas, dona y área
- Paleta de 10 colores para distinguir múltiples contratos
- Tooltips mejorados con formato de moneda y porcentajes
- Diseño responsive que se adapta a móviles y tablets
- Efectos hover y animaciones suaves

**🐛 Correcciones:**
- Gráfica `chartFacturacion` ahora se muestra correctamente con puntos visibles
- Gráfica `chartPacientes` renderiza con leyenda personalizada con porcentajes
- Canvas se recrean correctamente después de los indicadores de carga
- Validación de datos vacíos antes de intentar renderizar

**📊 Impacto Operativo:**
- **Análisis Financiero**: Visualización clara del valor facturado por cada contrato
- **Toma de Decisiones**: Acceso rápido a métricas clave y tendencias
- **Rendimiento Optimizado**: Carga selectiva de datos según la sección activa
- **Experiencia Mejorada**: Interfaz moderna con feedback visual constante

---

### v2.4 (Octubre 2024) - Mejoras en Dispensado y Sincronización

**🚀 Nuevas Funcionalidades:**
- Campo `formula_completa` agregado a la tabla `dispensado_medcol6` para mejor trazabilidad
- Función mejorada para sincronizar pendientes entregados con sistemas externos
- Optimización de la función `updateanuladosapi` para sincronización de facturas anuladas
- Nueva funcionalidad para gestión de pendientes vs dispensación

**🔧 Mejoras Técnicas:**
- Migración de base de datos: `2025_10_17_152002_add_formula_completa_to_dispensado_medcol6_table.php`
- Controlador `DispensadoApiMedcol6Controller.php` actualizado con mejoras de sincronización
- Modelo `DispensadoApiMedcol6.php` mejorado para soportar nueva estructura
- Vistas de dispensado actualizadas con campos adicionales

**🐛 Correcciones:**
- Mejora en la sincronización de pendientes entregados evitando duplicados
- Control de integridad en actualización de facturas anuladas
- Validaciones mejoradas en el proceso de dispensación

**📊 Impacto Operativo:**
- **Trazabilidad Completa**: Registro detallado de fórmulas completas en dispensación
- **Sincronización Confiable**: Menor tasa de errores en sincronización con APIs externas
- **Gestión Optimizada**: Mejor control del flujo pendientes → dispensado → entregado

---

### v2.3 (Septiembre 2024) - Interfaz de Pestañas Organizada para Smart Pendi

**🚀 Nuevas Funcionalidades:**
- Interfaz reorganizada con 3 pestañas principales para separar análisis
- Navegación optimizada con pestañas responsivas y animaciones suaves
- Botones de actualización independientes para cada análisis
- Diseño móvil mejorado con pestañas adaptativas

**🔧 Mejoras Técnicas:**
- Estructura de pestañas principales para mejor organización del contenido
- JavaScript refactorizado para manejo independiente de cada análisis
- CSS personalizado con efectos hover y transiciones fluidas
- Sistema de actualización granular por pestaña

**🎨 Mejoras de UX:**
- Reducción de sobrecarga visual con contenido organizado por pestañas
- Acceso directo a información específica sin desplazamiento innecesario
- Iconografía consistente y colores temáticos por análisis
- Navegación intuitiva con indicadores visuales claros

**📊 Impacto Operativo:**
- **3 Pestañas Principales**: Pendientes (0-48h), Sugerencias Inteligentes, Análisis de Inventario
- **Navegación Optimizada**: Eliminación del scroll excesivo y acceso directo por contexto
- **Actualización Granular**: Refresh independiente por análisis evita recargas innecesarias
- **Diseño Responsivo**: Adaptación completa desde móvil hasta desktop con breakpoints optimizados

---

### v2.2 (Agosto 2024) - Validaciones de Entrega y Filtros Avanzados

**🚀 Nuevas Funcionalidades:**
- Validación de fecha de entrega vs fecha de factura en actualizaciones masivas
- Filtro de búsqueda por documento/historia en gestión de pacientes
- Modal mejorado de gestión de pacientes con filtros personalizados

**🔧 Mejoras Técnicas:**
- Validación backend que previene fechas de entrega anteriores a fecha de factura
- Sistema de filtros más granular para búsquedas de pacientes
- Interfaz optimizada para gestión personalizada de pendientes

**🐛 Correcciones:**
- Control de integridad temporal en actualizaciones de pendientes
- Validación robusta de fechas en función `updateMultiplesPendientes`

---

### v2.1 (Enero 2024) - Optimización de Entregas Consolidadas

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

### 🆕 Últimas Actualizaciones (v2.5) - Dashboard Analytics

#### 📊 Dashboard Analytics Optimizado

##### ✨ Sistema Modular de Análisis
El nuevo Dashboard Analytics presenta una arquitectura completamente modular que permite:

- **Carga Asíncrona**: Solo se cargan los datos cuando el usuario selecciona cada sección
- **5 Módulos Independientes**: Cada análisis tiene su propio endpoint y caché
- **Arquitectura Optimizada**: Reducción significativa del tiempo de carga inicial
- **Experiencia Mejorada**: Feedback visual constante con spinners e indicadores

##### 📈 Gráfica de Valor Facturado por Contrato

**Nueva funcionalidad destacada** que muestra el valor total facturado agrupado por el campo `centroprod`:

```javascript
// Características principales
{
    tipo: "Gráfica de barras verticales",
    colores: "10 colores diferentes para distinguir contratos",
    datos: "Agrupados por campo centroprod de dispensado_medcol6",
    ordenamiento: "Descendente por valor facturado",
    formato: "Valores monetarios con separador de miles",
    interactividad: "Tooltips con formato detallado"
}
```

**Cálculo Inteligente de Valores**:
- Prioriza `valor_unitario` de tabla `analisis_nt` cuando existe
- Fallback a `precio_unitario * numero_unidades` de `dispensado_medcol6`
- Última opción: `valor_total` de dispensado

**Implementación**:
```php
// Endpoint: /dashboard/valor-por-contrato
// Controller: DashboardController@getValorPorContrato (línea 1029)
// Cache: 30 minutos para optimizar rendimiento
```

##### 🎯 Módulos del Dashboard

**1. Resumen General**
- Total de pacientes atendidos
- Valor total facturado
- Medicamentos diferentes dispensados
- Paciente con mayor valor
- Top 5 medicamentos más dispensados
- **NUEVO**: Valor total facturado por contrato (gráfica de barras)

**2. Análisis de Pendientes (Medcol6)**
- Valor pendiente por facturar
- Valor total entregado
- Estadísticas detalladas por estado (PENDIENTE, ENTREGADO, ANULADO, etc.)
- Tarjetas con valores y totales por categoría

**3. Distribución & Tendencias**
- **Facturación por Mes**: Gráfica de línea con área rellena
  - Puntos interactivos con hover effect
  - Formato de moneda en tooltips
  - Visualización de tendencias temporales

- **Distribución por Contrato**: Gráfica de dona
  - Porcentajes calculados automáticamente
  - Leyenda con cantidad de pacientes
  - 10 colores para soportar múltiples contratos

**4. Tendencias de Pendientes**
- Distribución por estado (gráfica de dona)
- Valor monetario por estado (gráfica de barras)
- Tendencias mensuales por estado (gráfica de líneas múltiples)
- Top 10 medicamentos pendientes (gráfica horizontal)
- DataTable completo con todos los medicamentos pendientes

**5. Reportes Detallados**
- Reporte de medicamentos con DataTables
- Reporte de pacientes con DataTables
- Exportación a Excel/PDF
- Búsqueda y filtros avanzados

##### 🔧 Mejoras Técnicas Implementadas

**Backend (DashboardController.php)**:
```php
// Nuevos endpoints modulares
Route::get('/dashboard/resumen-general', ...);          // Línea 69
Route::get('/dashboard/resumen-pendientes', ...);       // Línea 70
Route::get('/dashboard/analisis-distribucion', ...);    // Línea 71
Route::get('/dashboard/tendencias-pendientes', ...);    // Línea 72
Route::get('/dashboard/reportes-detallados', ...);      // Línea 73
Route::get('/dashboard/valor-por-contrato', ...);       // Línea 74 - NUEVO
```

**Sistema de Caché Inteligente**:
- Cache keys únicos por combinación de parámetros
- TTL de 30 minutos (1800 segundos)
- Invalidación automática al cambiar filtros
- Optimización de consultas pesadas

**Validación y Manejo de Errores**:
```javascript
// Antes: Sin validación
function updateChart(data) {
    // Renderiza directamente - FALLA si data es null
}

// Después: Con validación completa
function updateChart(data) {
    // 1. Verificar que canvas existe
    if (!ctx) return;

    // 2. Validar datos
    if (!data || data.length === 0) {
        showInfoMessage("No hay datos disponibles");
        return;
    }

    // 3. Renderizar con datos validados
    renderChart(data);
}
```

##### 📊 Estructura de Datos

**Endpoint `/dashboard/valor-por-contrato`**:
```json
[
    {
        "centroprod": "SOS",
        "total_facturado": 45678900.50
    },
    {
        "centroprod": "JAMUNDI",
        "total_facturado": 32145600.75
    }
]
```

**Endpoint `/dashboard/analisis-distribucion`**:
```json
{
    "facturas_por_mes": [
        {
            "mes": 10,
            "año": 2024,
            "total_mes": 78543200.25
        }
    ],
    "pacientes_por_contrato": [
        {
            "centroprod": "SOS",
            "total_pacientes": 1250
        }
    ]
}
```

##### 🎨 Experiencia de Usuario

**Interfaz Modular**:
- Menú de selección con 6 tarjetas interactivas
- Cada tarjeta activa su sección correspondiente
- Indicadores visuales de sección activa
- Diseño con gradientes y sombras modernas

**Feedback Visual**:
- Spinners de carga mientras se obtienen datos
- Mensajes informativos cuando no hay datos
- Mensajes de error amigables en caso de fallo
- Animaciones suaves con CSS transitions

**Accesibilidad**:
- Iconografía clara y consistente
- Tooltips descriptivos
- Formato de moneda en español
- Diseño responsive mobile-first

##### 📈 Beneficios Operativos

**Para Gerencia**:
- Visualización clara de valores facturados por contrato
- Identificación rápida de contratos más rentables
- Tendencias temporales para toma de decisiones
- Acceso a métricas clave en tiempo real

**Para Operaciones**:
- Análisis de distribución de pacientes
- Seguimiento de pendientes por estado
- Identificación de medicamentos más demandados
- Reportes exportables para auditoría

**Para TI**:
- Sistema de caché reduce carga del servidor
- Console logs facilitan debugging
- Manejo robusto de errores
- Código modular y mantenible

### 🆕 Actualizaciones Anteriores (v2.2)

#### 🔒 Sistema de Validación de Fechas de Entrega

##### ✨ Validación de Integridad Temporal
El sistema `updateMultiplesPendientes()` ahora incluye validación robusta para mantener la coherencia temporal:

- **Control de Fechas**: Previene que `fecha_entrega` sea anterior a `fecha_factura`
- **Validación Automática**: Verificación en tiempo real durante actualizaciones masivas
- **Mensajes Descriptivos**: Errores informativos que incluyen la fecha de factura como referencia

##### 🛡️ Implementación Técnica
```php
// Validación agregada en PendienteApiMedcol6Controller.php:2274-2283
if (!empty($pendienteData['fecha_entrega']) && !empty($pendiente->fecha_factura)) {
    $fechaEntrega = Carbon::parse($pendienteData['fecha_entrega']);
    $fechaFactura = Carbon::parse($pendiente->fecha_factura);
    
    if ($fechaEntrega->lt($fechaFactura)) {
        $errores[] = "ID {$pendienteData['id']}: Fecha de entrega no puede ser menor a fecha de factura ({$fechaFactura->format('d/m/Y')})";
        continue;
    }
}
```

##### 📋 Reglas de Negocio
- ✅ **Permitido**: `fecha_entrega` igual o posterior a `fecha_factura`
- ❌ **Bloqueado**: `fecha_entrega` anterior a `fecha_factura`
- ⚠️ **Comportamiento**: Continúa procesando otros registros en caso de error

#### 🔍 Sistema de Filtros Avanzados para Gestión de Pacientes

##### ✨ Nuevo Filtro de Documento/Historia
Mejora significativa en el modal de gestión de pacientes (`modalGestionPacientes.blade.php`):

- **Campo Personalizado**: Input dedicado para búsqueda por documento o historia clínica
- **Búsqueda Precisa**: Permite localizar pacientes específicos de manera directa
- **Interfaz Optimizada**: Diseño responsive con distribución equilibrada de columnas

##### 🎨 Estructura Mejorada de Filtros
```html
<!-- Antes: Solo fechas y farmacia -->
<div class="row">
    <div class="col-md-3">Fecha Inicial</div>
    <div class="col-md-3">Fecha Final</div>
    <div class="col-md-4">Farmacia</div>
    <div class="col-md-2">Buscar</div>
</div>

<!-- Después: Incluye filtro de documento -->
<div class="row">
    <div class="col-md-2">Fecha Inicial</div>
    <div class="col-md-2">Fecha Final</div>
    <div class="col-md-3">Documento / Historia</div>  <!-- NUEVO -->
    <div class="col-md-3">Farmacia</div>
    <div class="col-md-2">Buscar</div>
</div>
```

##### 📊 Beneficios Operativos
- **Búsqueda Directa**: Acceso inmediato a pacientes específicos por documento
- **Eficiencia Mejorada**: Reducción del tiempo de búsqueda manual
- **Experiencia de Usuario**: Interface más intuitiva y funcional
- **Compatibilidad**: Funciona en conjunto con filtros existentes

### 🆕 Actualizaciones Anteriores (v2.1)

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

### 🎯 Nueva Interfaz de Pestañas Organizadas (v2.3)

#### 🌟 Visión General de la Mejora
La nueva implementación transforma la experiencia de usuario al organizar los análisis en **3 pestañas principales independientes**, eliminando la sobrecarga visual y permitiendo acceso directo a información específica sin necesidad de desplazamiento innecesario.

#### 📑 Estructura de Pestañas

##### 1. 📊 **Pestaña "Pendientes en Ventana"**
- **Enfoque**: Análisis detallado de medicamentos pendientes en ventana crítica (0-48 horas)
- **Contenido**: DataTable completo con información de pacientes, medicamentos y tiempos
- **Funcionalidades**:
  - Tabla interactiva con paginación del lado del servidor
  - Búsqueda avanzada por múltiples campos
  - Botón de actualización independiente
  - Exportación a Excel/PDF directa
  - Ordenamiento dinámico por prioridad temporal

##### 2. 💡 **Pestaña "Sugerencias Inteligentes"** 
- **Enfoque**: Recomendaciones predictivas para optimización de entregas consolidadas
- **Contenido**: Listado priorizado de pacientes con múltiples medicamentos pendientes
- **Funcionalidades**:
  - Algoritmo de priorización automática
  - Información de contacto integrada
  - Cálculo de ventajas operativas
  - Actualización independiente de sugerencias
  - Guiones optimizados para contacto telefónico

##### 3. 📦 **Pestaña "Análisis por Inventario"**
- **Enfoque**: Clasificación de pacientes según disponibilidad de medicamentos
- **Contenido**: Sub-pestañas organizadas por estado de saldos
- **Sub-pestañas**:
  - **Con Saldo**: Pacientes con medicamentos disponibles para entrega inmediata
  - **Sin Saldo**: Pacientes que requieren gestión de compras/reposición
- **Funcionalidades**:
  - Contadores automáticos por categoría
  - Priorización basada en disponibilidad real
  - Indicadores visuales por estado de inventario

#### 🎨 Características de Diseño

##### ✨ Sistema de Navegación Responsivo
```html
<!-- Estructura de Pestañas Principales -->
<ul class="nav nav-tabs nav-tabs-custom">
    <li class="nav-item">
        <a class="nav-link active" href="#pendientes-panel">
            <i class="fas fa-clock text-primary"></i>
            <span class="d-none d-md-inline">Pendientes en Ventana</span>
            <span class="d-md-none">Pendientes</span>
            <small class="d-block text-muted">(0-48 Horas)</small>
        </a>
    </li>
    <!-- Pestañas adicionales con iconografía temática -->
</ul>
```

##### 🎯 Características Visuales
- **Iconografía Temática**: Cada pestaña tiene iconos específicos (reloj, bombilla, almacén)
- **Colores Diferenciados**: Esquemas de color únicos por tipo de análisis
- **Animaciones Fluidas**: Transiciones suaves con efectos `fadeInUp`
- **Efectos Hover**: Elevación visual y sombras dinámicas
- **Estados Activos**: Indicadores visuales claros del contexto actual

##### 📱 Diseño Responsivo Avanzado
```css
/* Adaptación para Móviles */
@media (max-width: 576px) {
    .nav-tabs-custom {
        flex-direction: column;  /* Pestañas apiladas verticalmente */
    }
    .nav-tabs-custom .nav-link {
        text-align: left;        /* Alineación horizontal */
        flex-direction: row;     /* Icono + texto en fila */
    }
}
```

#### ⚙️ Implementación Técnica

##### 🔧 JavaScript Refactorizado
```javascript
// Sistema de navegación mejorado
$('#btn-analysis').click(function() {
    $('#analysis-tabs-section').show();
    $('#pendientes-tab').tab('show');  // Activar pestaña específica
    
    if (pendientesTable) {
        pendientesTable.ajax.reload();
    }
});

// Actualización independiente por pestaña
$('#refresh-pendientes').click(function() {
    if (pendientesTable) {
        pendientesTable.ajax.reload();
    }
    showSuccessMessage('Datos de pendientes actualizados');
});
```

##### 🎨 CSS Personalizado
```css
/* Estilo de pestañas principales */
.nav-tabs-custom .nav-link {
    border: none;
    background-color: #f8f9fa;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
}

.nav-tabs-custom .nav-link.active {
    background-color: #007bff;
    color: white;
    box-shadow: 0 4px 12px rgba(0,123,255,0.3);
}
```

##### 📊 Manejo de Estados
- **Estado Activo**: Manejo inteligente de qué pestaña está visible
- **Actualización Selectiva**: Solo se actualizan los datos de la pestaña activa
- **Persistencia**: Mantiene el estado de cada pestaña independientemente
- **Loading States**: Indicadores de carga específicos por análisis

#### 🚀 Beneficios Operativos

##### 💪 Mejoras en Rendimiento
- **Carga Selectiva**: Solo se cargan datos cuando se accede a cada pestaña
- **Memoria Optimizada**: Reducción del DOM activo en pantalla
- **Navegación Rápida**: Cambio instantáneo entre análisis sin recarga
- **Actualización Granular**: Refresh independiente evita sobrecarga innecesaria

##### 👥 Mejoras en Experiencia de Usuario
- **Navegación Intuitiva**: Acceso directo al análisis deseado
- **Menos Scroll**: Eliminación del desplazamiento vertical excesivo
- **Contexto Claro**: Cada análisis tiene su espacio dedicado
- **Eficiencia**: Usuarios pueden enfocarse en un análisis específico

##### 📈 Beneficios para Operaciones
- **Flujo Optimizado**: Diferentes roles pueden usar pestañas específicas
- **Análisis Paralelo**: Múltiples usuarios pueden trabajar en diferentes pestañas
- **Mantenimiento**: Actualizaciones independientes evitan interrupciones
- **Escalabilidad**: Fácil adición de nuevos análisis como pestañas adicionales

#### 🔄 Flujo de Trabajo Mejorado

##### 📋 Proceso Recomendado
1. **🏠 Inicio**: Acceder al dashboard Smart Pendi
2. **📊 Análisis de Pendientes**: 
   - Clic en "Ejecutar Análisis" → Se abre pestaña de Pendientes
   - Revisar tabla interactiva con ordenamiento por horas
   - Exportar datos críticos si es necesario

3. **💡 Revisión de Sugerencias**:
   - Clic en "Ver Sugerencias" → Se abre pestaña de Sugerencias
   - Revisar pacientes priorizados para entregas consolidadas
   - Contactar pacientes usando botones integrados

4. **📦 Análisis de Inventario**:
   - Navegar a pestaña de Inventario
   - Revisar sub-pestañas de disponibilidad
   - Priorizar entregas según saldos disponibles

#### 🎯 Casos de Uso por Pestaña

##### 👨‍⚕️ Personal de Entregas → **Pestaña Pendientes**
- Foco en tiempos críticos y rutas de entrega
- Uso del DataTable para ordenamiento por urgencia
- Exportación de listas para planificación de rutas

##### 📞 Personal de Contacto → **Pestaña Sugerencias**
- Foco en pacientes con múltiples medicamentos
- Uso de guiones optimizados para llamadas
- Priorización de casos consolidados

##### 📊 Gestión de Inventario → **Pestaña Inventario**
- Foco en disponibilidad de medicamentos
- Separación clara entre disponibles y faltantes  
- Planificación de compras y reposiciones

#### 🔧 Archivos Modificados (v2.3)

```
resources/views/smart-pendi/dashboard.blade.php
├── Nueva estructura HTML de pestañas principales
├── JavaScript refactorizado para manejo independiente
├── CSS personalizado para navegación responsiva
└── Funciones de actualización granular por pestaña
```

#### 📱 Compatibilidad y Soporte

- **✅ Navegadores**: Chrome 80+, Firefox 75+, Safari 13+, Edge 85+
- **✅ Dispositivos**: Desktop, Tablet (landscape/portrait), Mobile
- **✅ Resoluciones**: 320px - 4K (responsive breakpoints optimizados)
- **✅ Accesibilidad**: ARIA labels, navegación por teclado, screen readers

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

### Archivos Modificados Recientemente

#### v2.4 (Octubre 2024) - Dispensado y Sincronización

**Backend:**
- `app/Http/Controllers/Medcol6/DispensadoApiMedcol6Controller.php`
  - Mejoras en funciones de sincronización de pendientes entregados
  - Optimización de `updateanuladosapi()` para facturas anuladas
- `app/Models/Medcol6/DispensadoApiMedcol6.php`
  - Soporte para campo `formula_completa`
  - Mejoras en relaciones y scope queries

**Base de Datos:**
- `database/migrations/2025_10_17_152002_add_formula_completa_to_dispensado_medcol6_table.php`
  - Nueva columna `formula_completa` en tabla `dispensado_medcol6`

**Frontend:**
- `resources/views/menu/Medcol6/form/dispensado/form.blade.php`
  - Formulario actualizado con campo de fórmula completa
- `resources/views/menu/Medcol6/indexDispensado.blade.php`
  - Vista mejorada con columnas adicionales
  - Interfaz optimizada para nueva funcionalidad

#### v2.2 (Agosto 2024) - Validaciones de Entrega

**Backend:**
- `app/Http/Controllers/Medcol6/PendienteApiMedcol6Controller.php`
  - Función `updateMultiplesPendientes()` con validación de fechas mejorada
  - Control de integridad temporal entre `fecha_entrega` y `fecha_factura`

**Frontend:**
- `resources/views/menu/Medcol6/modal/modalGestionPacientes.blade.php`
  - Nuevo filtro de búsqueda por documento/historia
  - Reorganización de columnas para mejor distribución visual
  - Campo de entrada con placeholder informativo

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

## 🔧 Herramientas y Scripts Auxiliares

### 📊 Actualización Masiva desde Excel (v2.8)

#### Descripción
Herramienta completa para actualizar masivamente registros de `pendiente_api_medcol6` desde archivos Excel, con validación automática y scripts de verificación.

#### Archivos Involucrados
```
📁 Raíz del proyecto
├── 📄 generate_update_query.py          # Script principal generador
├── 📄 read_excel_sheets.py              # Lector de Excel multi-hoja
├── 📄 update_pendientes_entregados.sql  # Consulta SQL generada
├── 📄 RESUMEN_CONSULTA_SQL.md           # Documentación completa
├── 📄 verificar_antes_de_actualizar.sql # Verificación pre-UPDATE
└── 📄 verificar_despues_de_actualizar.sql # Verificación post-UPDATE
```

#### Uso Rápido

**1. Preparar archivo Excel:**
```
Requisitos del Excel (Hoja1):
- Columna "unicos": Valor único concatenado (documento+factura+codigo)
- Columna "Dispensación": Formato CDIO66615 (letras + números)
- Columna "Fecha entrega": Fecha de entrega (múltiples formatos soportados)
```

**2. Generar consulta SQL:**
```bash
# Desde el directorio raíz del proyecto
python generate_update_query.py

# Salida esperada:
# ✓ Consulta SQL generada exitosamente
# - Archivo: update_pendientes_entregados.sql
# - Registros procesados: 2,450
```

**3. Verificar antes de ejecutar:**
```bash
mysql -u usuario -p database < verificar_antes_de_actualizar.sql

# Revisa:
# - Total de registros que se actualizarán
# - Estado actual de los primeros 10 registros
# - Distribución por estado actual
```

**4. Backup obligatorio:**
```bash
mysqldump -u usuario -p database pendiente_api_medcol6 > backup_$(date +%Y%m%d_%H%M%S).sql
```

**5. Ejecutar actualización:**
```bash
# Si la consulta es > 1MB, aumentar límite primero
mysql -u usuario -p -e "SET GLOBAL max_allowed_packet = 16777216;"

# Ejecutar el UPDATE
mysql -u usuario -p database < update_pendientes_entregados.sql
```

**6. Verificar después:**
```bash
mysql -u usuario -p database < verificar_despues_de_actualizar.sql

# Revisa:
# - Registros actualizados correctamente
# - Campos doc_entrega y factura_entrega llenos
# - Distribución de fechas de entrega
```

#### Ejemplo de Transformación

**Datos en Excel:**
| unicos | Dispensación | Fecha entrega |
|--------|--------------|---------------|
| MPE30187M000447-01 | CDIO66615 | 2025-10-15 00:00:00 |

**Query SQL generada:**
```sql
UPDATE pendiente_api_medcol6
SET
    estado = CASE
        WHEN CONCAT(documento, factura, codigo) = 'MPE30187M000447-01' THEN 'ENTREGADO'
        ELSE estado
    END,
    usuario = CASE
        WHEN CONCAT(documento, factura, codigo) = 'MPE30187M000447-01' THEN 'SYSTEM'
        ELSE usuario
    END,
    fecha_entrega = CASE
        WHEN CONCAT(documento, factura, codigo) = 'MPE30187M000447-01' THEN '2025-10-15 00:00:00'
        ELSE fecha_entrega
    END,
    doc_entrega = CASE
        WHEN CONCAT(documento, factura, codigo) = 'MPE30187M000447-01' THEN 'CDIO'
        ELSE doc_entrega
    END,
    factura_entrega = CASE
        WHEN CONCAT(documento, factura, codigo) = 'MPE30187M000447-01' THEN '66615'
        ELSE factura_entrega
    END,
    updated_at = NOW()
WHERE CONCAT(documento, factura, codigo) IN ('MPE30187M000447-01', ...);
```

**Resultado en BD:**
| documento | factura | codigo | estado | usuario | fecha_entrega | doc_entrega | factura_entrega |
|-----------|---------|--------|--------|---------|---------------|-------------|-----------------|
| MPE | 30187 | M000447-01 | ENTREGADO | SYSTEM | 2025-10-15 00:00:00 | CDIO | 66615 |

#### Troubleshooting

**Error: "Packet too large"**
```sql
-- Aumentar el límite de paquetes
SET GLOBAL max_allowed_packet = 16777216; -- 16MB
-- O en my.cnf/my.ini:
[mysqld]
max_allowed_packet = 16M
```

**Error: "No se pudo decodificar el JSON"**
```bash
# Regenerar el JSON limpio desde Excel
python read_excel_sheets.py
```

**Error: "Registros omitidos: X"**
- Revisar formato de campo "Dispensación" (debe ser letras + números)
- Verificar formato de fechas en Excel
- Consultar logs para ver qué registros fallaron

#### Parámetros Personalizables

Para adaptar el script a otras tablas o campos:

```python
# En generate_update_query.py

# Cambiar tabla destino (línea 103)
sql = "UPDATE pendiente_api_medcol6\n"  # ← Cambiar aquí

# Cambiar campos a actualizar (líneas 48-68)
# Modificar el regex de extracción (línea 36)
match = re.match(r'^([A-Za-z]+)(\d+)$', dispensacion)

# Cambiar validación WHERE (línea 127)
sql += "WHERE CONCAT(documento, factura, codigo) IN (\n"
```

#### Logs y Debugging

**Backend (storage/logs/laravel.log):**
```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log | grep "Actualización masiva"
```

**Frontend (Console del navegador):**
```javascript
// En caso de ejecutar vía web interface
console.log('Datos procesados:', datosFinales);
```

---

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.
