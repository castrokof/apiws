<?php

namespace App\Services;

use App\Models\Medcol6\SaldosMedcol6;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SaldosApiService
{
    private $apiUrl;
    private $email;
    private $password;

    public function __construct()
    {
        $this->apiUrl = 'http://hed08pf9dxt.sn.mynetname.net:8004/api';
        $this->email = 'castrokofdev@gmail.com';
        $this->password = 'colMed2023**';
    }

    /**
     * Autenticarse en la API externa
     */
    private function autenticar()
    {
        $response = Http::timeout(30)->post("{$this->apiUrl}/acceso", [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if (!$response->successful()) {
            throw new \Exception('Error al autenticarse en la API externa');
        }

        $data = $response->json();

        if (!isset($data['token'])) {
            throw new \Exception('Token de autenticación no recibido');
        }

        return $data['token'];
    }

    /**
     * Cerrar sesión en la API externa
     */
    private function cerrarSesion($token)
    {
        try {
            Http::withToken($token)->get("{$this->apiUrl}/closeallacceso");
        } catch (\Exception $e) {
            Log::warning('Error al cerrar sesión en API externa: ' . $e->getMessage());
        }
    }

    /**
     * Obtener datos de stock desde la API
     */
    private function obtenerStock($token)
    {
        Log::info('Intentando obtener stock con token', [
            'token_length' => strlen($token),
            'token_preview' => substr($token, 0, 20) . '...',
            'endpoint' => "{$this->apiUrl}/getStock"
        ]);

        // Probar diferentes métodos de autenticación
        $response = Http::timeout(60)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])
            ->get("{$this->apiUrl}/getStock");

        Log::info('Respuesta de getStock recibida', [
            'status_code' => $response->status(),
            'headers' => $response->headers(),
            'is_successful' => $response->successful()
        ]);

        if (!$response->successful()) {
            $statusCode = $response->status();
            $responseBody = $response->body();

            Log::error('Error HTTP al obtener stock desde la API', [
                'status_code' => $statusCode,
                'response_body' => substr($responseBody, 0, 1000),
                'endpoint' => "{$this->apiUrl}/getStock",
                'token_used' => substr($token, 0, 20) . '...'
            ]);

            throw new \Exception("Error HTTP {$statusCode} al obtener los datos de stock desde la API");
        }

        $responseBody = $response->body();
        Log::info('Respuesta cruda de la API getStock', [
            'response_length' => strlen($responseBody),
            'response_preview' => substr($responseBody, 0, 500),
            'endpoint' => "{$this->apiUrl}/getStock"
        ]);

        $data = $response->json();

        if ($data === null) {
            Log::error('Error al decodificar JSON de la API', [
                'json_error' => json_last_error_msg(),
                'response_preview' => substr($responseBody, 0, 500)
            ]);
            throw new \Exception('Error al decodificar la respuesta JSON de la API');
        }

        // La API devuelve un array directo de objetos, no envuelto en una clave 'data'
        if (!is_array($data)) {
            Log::error('La respuesta no es un array', [
                'data_type' => gettype($data),
                'response_structure' => is_object($data) ? array_keys((array)$data) : 'not_object'
            ]);
            throw new \Exception('Formato de respuesta inválido: se esperaba un array de registros');
        }

        Log::info('Estructura de respuesta JSON validada', [
            'is_array' => is_array($data),
            'total_records' => count($data),
            'first_item_keys' => count($data) > 0 ? array_keys($data[0]) : []
        ]);

        return $data;
    }

    /**
     * Limpiar registros existentes del día actual
     */
    private function limpiarRegistrosAnteriores($fechaActual)
    {
        $registrosBorrados = SaldosMedcol6::whereDate('fecha_saldo', $fechaActual)->delete();

        Log::info('Registros de saldos anteriores eliminados', [
            'cantidad' => $registrosBorrados,
            'fecha' => $fechaActual
        ]);

        return $registrosBorrados;
    }

    /**
     * Procesar y insertar datos de saldos
     */
    private function procesarSaldos($saldosApi, $fechaActual)
    {
        $contador = 0;
        $errores = 0;
        $totalRegistros = count($saldosApi);

        // Ajustar memoria según la cantidad de registros
        if ($totalRegistros > 10000) {
            ini_set('memory_limit', '1024M');
        }

        $saldosParaInsertar = [];

        foreach ($saldosApi as $item) {
            try {
                // Validar campos requeridos
                if (empty($item['codigo']) || empty($item['nombre'])) {
                    $errores++;
                    continue;
                }

                // Filtrar códigos que no son elementos importantes
                if (in_array($item['codigo'], ['1010', '1011', '1012'])) {
                    continue;
                }

                $saldosParaInsertar[] = [
                    'ips' => trim($item['ips'] ?? ''),
                    'deposito' => trim($item['deposito'] ?? ''),
                    'agrupador' => trim($item['agrupador'] ?? ''),
                    'codigo' => trim($item['codigo']),
                    'cums' => trim($item['cums'] ?? ''), // Puede no estar presente en todos los registros
                    'nombre' => trim($item['nombre']),
                    'marca' => trim($item['marca'] ?? ''),
                    'costo_unitario' => $this->parseDecimal($item['costo_unitario'] ?? 0),
                    'saldo' => $this->parseDecimal($item['saldo'] ?? 0),
                    'total' => $this->parseDecimal($item['total'] ?? 0),
                    'fecha_vencimiento' => $this->parseDate($item['fecha_vencimiento'] ?? null),
                    'invima' => trim($item['invima'] ?? ''),
                    'fecha_saldo' => $fechaActual,
                    'grupo' => trim($item['grupo'] ?? ''),
                    'subgrupo' => trim($item['subgrupo'] ?? ''),
                    'linea' => trim($item['Linea'] ?? ''), // Nota: capital L en la API
                    'nombre_ips' => trim($item['nombre_ips'] ?? ''),
                    'nombre_deposito' => trim($item['nombre_deposito'] ?? ''),
                    'nombre_grupo' => trim($item['nombre_grupo'] ?? ''),
                    'nombre_subgrupo' => trim($item['nombre_subgrupo'] ?? ''),
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $contador++;

                // Insertar en chunks de 500 registros
                if (count($saldosParaInsertar) >= 500) {
                    SaldosMedcol6::insert($saldosParaInsertar);
                    $saldosParaInsertar = [];
                }
            } catch (\Exception $e) {
                $errores++;
                Log::warning('Error procesando registro de saldo', [
                    'error' => $e->getMessage(),
                    'registro' => $item
                ]);
            }
        }

        // Insertar registros restantes
        if (!empty($saldosParaInsertar)) {
            SaldosMedcol6::insert($saldosParaInsertar);
        }

        return [$contador, $errores];
    }

    /**
     * Probar conexión y estructura de respuesta de la API
     */
    public function probarApi($usuario = 'system')
    {
        Log::info('Iniciando prueba de API getStock', [
            'usuario' => $usuario,
            'endpoint' => $this->apiUrl . '/getStock'
        ]);

        try {
            // Autenticarse
            $token = $this->autenticar();
            Log::info('Autenticación exitosa para prueba', ['usuario' => $usuario]);

            try {
                // Obtener solo los primeros datos para diagnóstico
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json'
                    ])
                    ->get("{$this->apiUrl}/getStock");

                $diagnostico = [
                    'status_code' => $response->status(),
                    'headers' => $response->headers(),
                    'body_length' => strlen($response->body()),
                    'body_preview' => substr($response->body(), 0, 1000),
                    'is_json' => $response->json() !== null,
                ];

                if ($response->successful() && $response->json() !== null) {
                    $data = $response->json();

                    if (is_array($data)) {
                        $diagnostico['data_type'] = 'array';
                        $diagnostico['data_count'] = count($data);
                        if (count($data) > 0) {
                            $diagnostico['first_item_keys'] = array_keys($data[0]);
                            $diagnostico['first_item_sample'] = array_slice($data[0], 0, 5);
                        }
                    } else {
                        $diagnostico['json_keys'] = is_object($data) ? array_keys((array)$data) : [];
                        $diagnostico['data_type'] = gettype($data);
                    }
                }

                Log::info('Diagnóstico completo de API getStock', $diagnostico);

                return [
                    'success' => true,
                    'message' => 'Prueba de API completada',
                    'diagnostico' => $diagnostico
                ];
            } finally {
                $this->cerrarSesion($token);
            }
        } catch (\Exception $e) {
            Log::error('Error en prueba de API getStock', [
                'error' => $e->getMessage(),
                'usuario' => $usuario,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error en la prueba: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Sincronizar saldos desde la API externa
     */
    public function sincronizar($usuario = 'system')
    {
        Log::info('Iniciando sincronización de saldos', [
            'usuario' => $usuario,
            'endpoint' => $this->apiUrl . '/getStock'
        ]);

        try {
            // Configurar límites para el proceso
            set_time_limit(0);
            ini_set('memory_limit', '512M');

            // Autenticarse
            $token = $this->autenticar();
            Log::info('Autenticación exitosa', ['usuario' => $usuario]);

            try {
                // Obtener datos de stock
                $saldosApi = $this->obtenerStock($token);
                $totalRegistros = count($saldosApi);

                Log::info('Datos obtenidos de la API', [
                    'total_registros' => $totalRegistros,
                    'usuario' => $usuario
                ]);

                // Procesar datos
                $fechaActual = Carbon::now()->format('Y-m-d');

                // IMPORTANTE: Obtener items anteriores ANTES de limpiar
                $itemsAnteriores = $this->obtenerItemsAnteriores();

                $this->limpiarRegistrosAnteriores($fechaActual);
                [$contador, $errores] = $this->procesarSaldos($saldosApi, $fechaActual);

                // Insertar registros con saldo cero para items que desaparecieron
                $contadorSaldosCero = $this->insertarSaldosCero($itemsAnteriores, $saldosApi, $fechaActual);

                $mensaje = "Sincronización completada: {$contador} registros procesados";
                if ($contadorSaldosCero > 0) {
                    $mensaje .= ", {$contadorSaldosCero} items con saldo cero";
                }
                if ($errores > 0) {
                    $mensaje .= ", {$errores} errores";
                }

                Log::info('Sincronización de saldos completada', [
                    'registros_procesados' => $contador,
                    'saldos_cero_insertados' => $contadorSaldosCero,
                    'errores' => $errores,
                    'usuario' => $usuario,
                    'fecha' => $fechaActual
                ]);

                return [
                    'success' => true,
                    'message' => $mensaje,
                    'data' => [
                        'registros_procesados' => $contador,
                        'saldos_cero_insertados' => $contadorSaldosCero,
                        'errores' => $errores,
                        'fecha_sincronizacion' => $fechaActual
                    ]
                ];
            } finally {
                // Siempre cerrar sesión
                $this->cerrarSesion($token);
            }
        } catch (\Exception $e) {
            Log::error('Error en sincronización de saldos', [
                'error' => $e->getMessage(),
                'usuario' => $usuario,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error en la sincronización: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtener estadísticas de saldos
     */
    public function obtenerEstadisticas()
    {
        try {
            $fechaActual = Carbon::now()->format('Y-m-d');

            // Estadísticas generales
            $totalProductos = SaldosMedcol6::whereDate('fecha_saldo', $fechaActual)->count();
            $productosConSaldo = SaldosMedcol6::whereDate('fecha_saldo', $fechaActual)
                ->where('saldo', '>', 0)
                ->count();
            $proximosVencer = SaldosMedcol6::whereDate('fecha_saldo', $fechaActual)
                ->whereBetween('fecha_vencimiento', [
                    Carbon::now(),
                    Carbon::now()->addDays(30)
                ])
                ->count();
            $vencidos = SaldosMedcol6::whereDate('fecha_saldo', $fechaActual)
                ->where('fecha_vencimiento', '<', Carbon::now())
                ->count();

            // Estadísticas por depósito
            $porDeposito = SaldosMedcol6::whereDate('fecha_saldo', $fechaActual)
                ->select('deposito', 'nombre_deposito', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('deposito', 'nombre_deposito')
                ->orderBy('total', 'desc')
                ->get();

            // Estadísticas por grupo
            $porGrupo = SaldosMedcol6::whereDate('fecha_saldo', $fechaActual)
                ->select('grupo', 'nombre_grupo', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('grupo', 'nombre_grupo')
                ->orderBy('total', 'desc')
                ->get();

            return [
                'success' => true,
                'data' => [
                    'resumen' => [
                        'total_productos' => $totalProductos,
                        'productos_con_saldo' => $productosConSaldo,
                        'proximos_vencer' => $proximosVencer,
                        'vencidos' => $vencidos,
                        'fecha_ultima_sincronizacion' => $fechaActual
                    ],
                    'por_deposito' => $porDeposito,
                    'por_grupo' => $porGrupo
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error obteniendo estadísticas de saldos', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ];
        }
    }

    /**
     * Obtener items de sincronizaciones anteriores (último mes)
     * Retorna un array indexado por "codigo|deposito" => datos_del_item
     * Usa el registro más reciente de cada item dentro del rango de fechas
     */
    private function obtenerItemsAnteriores()
    {
        try {
            $fechaActual = Carbon::now()->format('Y-m-d');
            $fechaInicio = Carbon::now()->subDays(30)->format('Y-m-d'); // Último mes

            Log::info('Buscando items anteriores en rango de fechas', [
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaActual
            ]);

            // Obtener todos los registros del último mes ordenados por fecha descendente
            $registros = SaldosMedcol6::whereDate('fecha_saldo', '>=', $fechaInicio)
                ->whereDate('fecha_saldo', '<=', $fechaActual)
                ->orderBy('fecha_saldo', 'desc')
                ->get();

            if ($registros->isEmpty()) {
                Log::info('No hay sincronizaciones anteriores en el último mes');
                return [];
            }

            // Construir array asociativo manualmente con arrays de PHP
            $itemsAnteriores = [];
            $codigosProcesados = [];
            $distribucionFechas = [];
            $itemsPorCodigo = [];

            foreach ($registros as $registro) {
                // Validar que tenga código válido
                if (empty($registro->codigo)) {
                    continue;
                }

                // Crear clave única
                $clave = trim($registro->codigo) . '|' . trim($registro->deposito ?? '');

                // Solo guardar el primer registro (más reciente) de cada codigo|deposito
                if (!isset($itemsAnteriores[$clave])) {
                    $itemsAnteriores[$clave] = $registro;

                    // Contar para estadísticas
                    $fecha = $registro->fecha_saldo instanceof Carbon
                        ? $registro->fecha_saldo->format('Y-m-d')
                        : $registro->fecha_saldo;

                    $distribucionFechas[$fecha] = ($distribucionFechas[$fecha] ?? 0) + 1;

                    // Agrupar por código
                    $codigo = trim($registro->codigo);
                    if (!isset($itemsPorCodigo[$codigo])) {
                        $itemsPorCodigo[$codigo] = [];
                    }
                    $itemsPorCodigo[$codigo][] = $registro;
                }
            }

            // Ordenar distribución de fechas
            arsort($distribucionFechas);
            $distribucionTop5 = array_slice($distribucionFechas, 0, 5, true);

            // Contar items con múltiples depósitos
            $itemsMultideposito = 0;
            $ejemploMultideposito = null;
            foreach ($itemsPorCodigo as $codigo => $items) {
                if (count($items) > 1) {
                    $itemsMultideposito++;
                    if ($ejemploMultideposito === null) {
                        $ejemploMultideposito = array_map(function($item) {
                            return [
                                'codigo' => $item->codigo,
                                'deposito' => $item->deposito,
                                'saldo' => $item->saldo,
                                'fecha_saldo' => $item->fecha_saldo instanceof Carbon
                                    ? $item->fecha_saldo->format('Y-m-d')
                                    : $item->fecha_saldo
                            ];
                        }, array_slice($items, 0, 3));
                    }
                }
            }

            // Obtener fechas min y max
            $fechas = array_map(function($item) {
                return $item->fecha_saldo instanceof Carbon
                    ? $item->fecha_saldo->format('Y-m-d')
                    : $item->fecha_saldo;
            }, $itemsAnteriores);

            Log::info('Items anteriores obtenidos - Detalle', [
                'rango_fechas' => $fechaInicio . ' a ' . $fechaActual,
                'total_registros' => count($itemsAnteriores),
                'items_unicos' => count($itemsPorCodigo),
                'items_con_multiples_depositos' => $itemsMultideposito,
                'distribucion_fechas_top5' => $distribucionTop5,
                'fecha_mas_reciente' => !empty($fechas) ? max($fechas) : null,
                'fecha_mas_antigua' => !empty($fechas) ? min($fechas) : null,
                'ejemplo_multideposito' => $ejemploMultideposito
            ]);

            return $itemsAnteriores;
        } catch (\Exception $e) {
            Log::error('Error al obtener items anteriores', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    /**
     * Insertar registros con saldo cero para items que desaparecieron de la API
     * Esto evita confusión cuando un item ya no tiene saldo en el depósito
     */
    private function insertarSaldosCero($itemsAnteriores, $itemsActuales, $fechaActual)
    {
        try {
            $contadorSaldosCero = 0;
            $saldosCeroParaInsertar = [];
            $itemsDesaparecidos = [];

            // Asegurar que tenemos algo iterable
            if (empty($itemsAnteriores)) {
                Log::info('No hay items anteriores para comparar');
                return 0;
            }

            // Crear un set de claves para búsqueda rápida
            $clavesActuales = [];
            $depositosPorCodigo = []; // Para debugging

            foreach ($itemsActuales as $item) {
                // Validar que existan los campos necesarios
                if (!isset($item['codigo'])) {
                    continue;
                }

                $codigo = trim($item['codigo']);
                $deposito = trim($item['deposito'] ?? '');
                $clave = $codigo . '|' . $deposito;
                $clavesActuales[$clave] = true;

                // Agrupar depósitos por código para logging
                if (!isset($depositosPorCodigo[$codigo])) {
                    $depositosPorCodigo[$codigo] = [];
                }
                $depositosPorCodigo[$codigo][] = $deposito;
            }

            Log::info('Análisis de items actuales', [
                'total_registros' => count($itemsActuales),
                'claves_unicas' => count($clavesActuales),
                'codigos_unicos' => count($depositosPorCodigo),
                'items_con_multiples_depositos' => count(array_filter($depositosPorCodigo, function($deps) {
                    return count($deps) > 1;
                }))
            ]);

            // Comparar items anteriores con los actuales
            foreach ($itemsAnteriores as $claveAnterior => $itemAnterior) {
                // Determinar si es objeto o array
                $esObjeto = is_object($itemAnterior);

                // Obtener valores de forma segura
                $codigoAnterior = $esObjeto
                    ? (isset($itemAnterior->codigo) ? trim($itemAnterior->codigo) : null)
                    : (isset($itemAnterior['codigo']) ? trim($itemAnterior['codigo']) : null);

                $depositoAnterior = $esObjeto
                    ? (isset($itemAnterior->deposito) ? trim($itemAnterior->deposito) : '')
                    : (isset($itemAnterior['deposito']) ? trim($itemAnterior['deposito']) : '');

                // Validar que el item tenga código válido
                if (empty($codigoAnterior)) {
                    Log::warning('Item anterior sin código válido', [
                        'clave' => $claveAnterior,
                        'es_objeto' => $esObjeto
                    ]);
                    continue;
                }

                // Reconstruir la clave para asegurar consistencia
                $claveVerificada = $codigoAnterior . '|' . $depositoAnterior;

                // Si el item-deposito no aparece en la respuesta actual de la API
                if (!isset($clavesActuales[$claveVerificada])) {
                    // Obtener depósitos actuales de forma segura
                    $depositosActuales = [];
                    if (isset($depositosPorCodigo[$codigoAnterior])) {
                        $depositosActuales = $depositosPorCodigo[$codigoAnterior];
                    }

                    // Función helper para obtener valor
                    $obtenerValor = function($item, $campo, $default = null) use ($esObjeto) {
                        if ($esObjeto) {
                            return isset($item->$campo) ? $item->$campo : $default;
                        }
                        return isset($item[$campo]) ? $item[$campo] : $default;
                    };

                    // Guardar para logging
                    $itemsDesaparecidos[] = [
                        'codigo' => $codigoAnterior,
                        'deposito' => $depositoAnterior,
                        'nombre' => substr($obtenerValor($itemAnterior, 'nombre', 'Sin nombre'), 0, 50),
                        'saldo_anterior' => $obtenerValor($itemAnterior, 'saldo', 0),
                        'depositos_actuales' => $depositosActuales
                    ];

                    $saldosCeroParaInsertar[] = [
                        'ips' => $obtenerValor($itemAnterior, 'ips', ''),
                        'deposito' => $depositoAnterior,
                        'agrupador' => $obtenerValor($itemAnterior, 'agrupador', ''),
                        'codigo' => $codigoAnterior,
                        'cums' => $obtenerValor($itemAnterior, 'cums', ''),
                        'nombre' => $obtenerValor($itemAnterior, 'nombre', ''),
                        'marca' => $obtenerValor($itemAnterior, 'marca', ''),
                        'costo_unitario' => $obtenerValor($itemAnterior, 'costo_unitario', 0),
                        'saldo' => 0, // ← SALDO EN CERO
                        'total' => 0, // ← TOTAL EN CERO
                        'fecha_vencimiento' => $obtenerValor($itemAnterior, 'fecha_vencimiento', null),
                        'invima' => $obtenerValor($itemAnterior, 'invima', ''),
                        'fecha_saldo' => $fechaActual,
                        'grupo' => $obtenerValor($itemAnterior, 'grupo', ''),
                        'subgrupo' => $obtenerValor($itemAnterior, 'subgrupo', ''),
                        'linea' => $obtenerValor($itemAnterior, 'linea', ''),
                        'nombre_ips' => $obtenerValor($itemAnterior, 'nombre_ips', ''),
                        'nombre_deposito' => $obtenerValor($itemAnterior, 'nombre_deposito', ''),
                        'nombre_grupo' => $obtenerValor($itemAnterior, 'nombre_grupo', ''),
                        'nombre_subgrupo' => $obtenerValor($itemAnterior, 'nombre_subgrupo', ''),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    $contadorSaldosCero++;

                    // Insertar en chunks de 500 registros
                    if (count($saldosCeroParaInsertar) >= 5000) {
                        SaldosMedcol6::insert($saldosCeroParaInsertar);
                        $saldosCeroParaInsertar = [];
                    }
                }
            }

            // Insertar registros restantes
            if (!empty($saldosCeroParaInsertar)) {
                SaldosMedcol6::insert($saldosCeroParaInsertar);
            }

            Log::info('Registros con saldo cero insertados - Resumen', [
                'total' => $contadorSaldosCero,
                'fecha' => $fechaActual,
                'primeros_10_items' => array_slice($itemsDesaparecidos, 0, 10)
            ]);

            // Log detallado de items en múltiples depósitos que perdieron saldo
            $itemsMultidepositoCero = array_filter($itemsDesaparecidos, function($item) {
                return !empty($item['depositos_actuales']); // El código aún existe en otros depósitos
            });

            if (!empty($itemsMultidepositoCero)) {
                Log::info('Items con saldo cero en depósito específico (aún existen en otros)', [
                    'total' => count($itemsMultidepositoCero),
                    'ejemplos' => array_slice($itemsMultidepositoCero, 0, 5)
                ]);
            }

            return $contadorSaldosCero;
        } catch (\Exception $e) {
            Log::error('Error al insertar saldos cero', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 0;
        }
    }

    /**
     * Funciones auxiliares privadas
     */
    private function parseDecimal($value)
    {
        if (is_null($value) || $value === '') {
            return 0;
        }

        // Remover caracteres no numéricos excepto punto y coma
        $cleaned = preg_replace('/[^\d.,]/', '', $value);

        // Convertir coma a punto si es necesario
        $cleaned = str_replace(',', '.', $cleaned);

        return floatval($cleaned);
    }

    private function parseDate($value)
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
