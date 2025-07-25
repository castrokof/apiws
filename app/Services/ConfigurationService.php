<?php

namespace App\Services;

/**
 * Servicio de configuración centralizada
 * 
 * Este servicio centraliza todas las configuraciones y mapeos
 * para evitar duplicación de código y facilitar el mantenimiento.
 */
class ConfigurationService
{
    /**
     * Mapeo de droguerías por ID de usuario
     *
     * @var array
     */
    private const DRUGSTORE_MAPPING = [
        '1' => ['code' => null, 'name' => 'Todas las droguerías'],
        '2' => ['code' => 'SM01', 'name' => 'Salud Mental'],
        '3' => ['code' => 'DLR1', 'name' => 'Dolor'],
        '4' => ['code' => 'PAC', 'name' => 'Pacífico'],
        '5' => ['code' => 'EHU1', 'name' => 'Evaristo García'],
        '6' => ['code' => 'BIO1', 'name' => 'Bioclínica'],
        '8' => ['code' => 'EM01', 'name' => 'EMCALI'],
        '9' => ['code' => 'BPDT', 'name' => 'Buenaventura PDT'],
        '10' => ['code' => 'DPA1', 'name' => 'Droguería Pacífico 1'],
        '11' => ['code' => 'EVSM', 'name' => 'Evaristo Salud Mental'],
        '12' => ['code' => 'EVEN', 'name' => 'Evaristo Enfermería'],
        '13' => ['code' => 'FRJA', 'name' => 'Farmacia Jamundí'],
    ];

    /**
     * Estados válidos para pendientes
     *
     * @var array
     */
    private const VALID_STATES = [
        'PENDIENTE' => [
            'label' => 'Pendiente',
            'icon' => '📋',
            'color' => 'warning',
            'description' => 'Medicamento en estado pendiente de gestión'
        ],
        'ENTREGADO' => [
            'label' => 'Entregado',
            'icon' => '✅',
            'color' => 'success',
            'description' => 'Medicamento entregado al paciente'
        ],
        'DESABASTECIDO' => [
            'label' => 'Desabastecido',
            'icon' => '❌',
            'color' => 'danger',
            'description' => 'Medicamento no disponible en inventario'
        ],
        'ANULADO' => [
            'label' => 'Anulado',
            'icon' => '🚫',
            'color' => 'secondary',
            'description' => 'Registro anulado por el sistema'
        ],
        'TRAMITADO' => [
            'label' => 'Tramitado',
            'icon' => '📋',
            'color' => 'info',
            'description' => 'En proceso de gestión'
        ]
    ];

    /**
     * Configuración de API externa
     *
     * @var array
     */
    private const API_CONFIG = [
        'base_url' => 'http://hed08pf9dxt.sn.mynetname.net:8004/api',
        'timeout' => 30,
        'retry_attempts' => 3,
        'endpoints' => [
            'login' => '/acceso',
            'pendientes' => '/pendientesapi',
            'anulados' => '/pendientesanuladosapi',
            'entregados' => '/entregados',
            'logout' => '/closeallacceso'
        ]
    ];

    /**
     * Obtener el código de droguería por ID de usuario
     *
     * @param string $userId
     * @return string|null
     */
    public function getDrugstoreCode(string $userId): ?string
    {
        return self::DRUGSTORE_MAPPING[$userId]['code'] ?? null;
    }

    /**
     * Obtener el nombre de droguería por ID de usuario
     *
     * @param string $userId
     * @return string
     */
    public function getDrugstoreName(string $userId): string
    {
        return self::DRUGSTORE_MAPPING[$userId]['name'] ?? 'Droguería desconocida';
    }

    /**
     * Obtener todas las droguerías
     *
     * @return array
     */
    public function getAllDrugstores(): array
    {
        return self::DRUGSTORE_MAPPING;
    }

    /**
     * Verificar si un usuario tiene acceso a todas las droguerías
     *
     * @param string $userId
     * @return bool
     */
    public function hasAccessToAllDrugstores(string $userId): bool
    {
        return $userId === '1';
    }

    /**
     * Obtener información de un estado
     *
     * @param string $state
     * @return array|null
     */
    public function getStateInfo(string $state): ?array
    {
        return self::VALID_STATES[strtoupper($state)] ?? null;
    }

    /**
     * Obtener todos los estados válidos
     *
     * @return array
     */
    public function getAllStates(): array
    {
        return self::VALID_STATES;
    }

    /**
     * Verificar si un estado es válido
     *
     * @param string $state
     * @return bool
     */
    public function isValidState(string $state): bool
    {
        return array_key_exists(strtoupper($state), self::VALID_STATES);
    }

    /**
     * Obtener configuración de API
     *
     * @param string|null $key
     * @return mixed
     */
    public function getApiConfig(?string $key = null)
    {
        if ($key === null) {
            return self::API_CONFIG;
        }

        return self::API_CONFIG[$key] ?? null;
    }

    /**
     * Obtener URL completa de endpoint de API
     *
     * @param string $endpoint
     * @return string
     */
    public function getApiEndpoint(string $endpoint): string
    {
        $baseUrl = self::API_CONFIG['base_url'];
        $endpointPath = self::API_CONFIG['endpoints'][$endpoint] ?? $endpoint;
        
        return $baseUrl . $endpointPath;
    }

    /**
     * Obtener configuración de validación por estado
     *
     * @param string $state
     * @return array
     */
    public function getValidationRulesByState(string $state): array
    {
        $baseRules = [
            'estado' => 'required|in:' . implode(',', array_keys(self::VALID_STATES)),
            'observacion' => 'required|string|min:3|max:500'
        ];

        switch (strtoupper($state)) {
            case 'ENTREGADO':
                return array_merge($baseRules, [
                    'cantord' => 'required|numeric|min:1',
                    'cantdpx' => 'required|numeric|min:1|lte:cantord',
                    'fecha_entrega' => 'required|date|before_or_equal:today',
                    'factura_entrega' => 'required|string|max:50',
                    'doc_entrega' => 'required|string|max:50'
                ]);

            case 'DESABASTECIDO':
                return array_merge($baseRules, [
                    'fecha_impresion' => 'required|date|before_or_equal:today'
                ]);

            case 'ANULADO':
                return array_merge($baseRules, [
                    'fecha_anulado' => 'required|date|before_or_equal:today'
                ]);

            default:
                return $baseRules;
        }
    }

    /**
     * Obtener mensajes de validación localizados
     *
     * @return array
     */
    public function getValidationMessages(): array
    {
        return [
            'estado.required' => 'El estado es requerido',
            'estado.in' => 'El estado seleccionado no es válido',
            'observacion.required' => 'Las observaciones son requeridas',
            'observacion.min' => 'Las observaciones deben tener al menos :min caracteres',
            'observacion.max' => 'Las observaciones no pueden exceder :max caracteres',
            'cantord.required' => 'La cantidad ordenada es requerida',
            'cantord.min' => 'La cantidad ordenada debe ser mayor a cero',
            'cantord.numeric' => 'La cantidad ordenada debe ser un número',
            'cantdpx.required' => 'La cantidad entregada es requerida',
            'cantdpx.min' => 'La cantidad entregada debe ser mayor a cero',
            'cantdpx.numeric' => 'La cantidad entregada debe ser un número',
            'cantdpx.lte' => 'La cantidad entregada no puede ser mayor a la cantidad ordenada',
            'fecha_entrega.required' => 'La fecha de entrega es requerida',
            'fecha_entrega.date' => 'La fecha de entrega debe ser una fecha válida',
            'fecha_entrega.before_or_equal' => 'La fecha de entrega no puede ser futura',
            'factura_entrega.required' => 'La factura de entrega es requerida',
            'factura_entrega.max' => 'La factura de entrega no puede exceder :max caracteres',
            'doc_entrega.required' => 'El documento de entrega es requerido',
            'doc_entrega.max' => 'El documento de entrega no puede exceder :max caracteres',
            'fecha_impresion.required' => 'La fecha de tramitado es requerida',
            'fecha_impresion.date' => 'La fecha de tramitado debe ser una fecha válida',
            'fecha_impresion.before_or_equal' => 'La fecha de tramitado no puede ser futura',
            'fecha_anulado.required' => 'La fecha de anulación es requerida',
            'fecha_anulado.date' => 'La fecha de anulación debe ser una fecha válida',
            'fecha_anulado.before_or_equal' => 'La fecha de anulación no puede ser futura',
        ];
    }

    /**
     * Obtener configuración de paginación
     *
     * @return array
     */
    public function getPaginationConfig(): array
    {
        return [
            'per_page' => 50,
            'max_per_page' => 100,
            'show_all_option' => true
        ];
    }

    /**
     * Obtener configuración de exportación
     *
     * @return array
     */
    public function getExportConfig(): array
    {
        return [
            'max_rows' => 10000,
            'allowed_formats' => ['xlsx', 'csv', 'pdf'],
            'timeout' => 300, // 5 minutos
            'chunk_size' => 1000
        ];
    }
}