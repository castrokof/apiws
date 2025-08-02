<?php

namespace App\Services;

use App\Models\Medcol6\PendienteApiMedcol6;
use App\Models\Medcol6\EntregadosApiMedcol6;
use App\Models\Medcol6\ObservacionesApiMedcol6;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

/**
 * Servicio para la gestión de pendientes Medcol6
 * 
 * Este servicio encapsula la lógica de negocio para la gestión de medicamentos pendientes,
 * siguiendo el principio de responsabilidad única (SRP) y mejorando la mantenibilidad.
 */
class PendienteService
{
    /**
     * Actualizar un pendiente
     *
     * @param int $id
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updatePendiente(int $id, array $data): array
    {
        try {
            DB::beginTransaction();

            $pendiente = PendienteApiMedcol6::findOrFail($id);
            
            // Aplicar las reglas de negocio según el estado
            $processedData = $this->processBusinessRules($pendiente, $data);
            
            // Actualizar el pendiente
            $pendiente->update($processedData);
            
            // Crear observación si es necesario
            if (!empty($data['observacion'])) {
                $this->createObservacion($pendiente, $data['observacion']);
            }
            
            // Crear registro de entregado si aplica
            if ($data['estado'] === 'ENTREGADO') {
                Log::info('Creating entregado record', [
                    'pendiente_id' => $pendiente->id,
                    'user' => Auth::user()->email ?? 'unknown'
                ]);
                $this->createEntregado($pendiente, $data);
            }

            DB::commit();

            return [
                'success' => 'ok1',
                'message' => 'Pendiente actualizado exitosamente',
                'data' => $pendiente->fresh()
            ];

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Procesar las reglas de negocio según el estado
     *
     * @param PendienteApiMedcol6 $pendiente
     * @param array $data
     * @return array
     */
    private function processBusinessRules(PendienteApiMedcol6 $pendiente, array $data): array
    {
        $processedData = $data;
        $processedData['usuario'] = Auth::user()->name;
        $processedData['updated_at'] = now();

        switch ($data['estado']) {
            case 'ENTREGADO':
                $processedData = $this->processEntregadoState($processedData);
                break;
                
            case 'DESABASTECIDO':
                $processedData = $this->processDesabastecidoState($processedData);
                break;
                
            case 'ANULADO':
                $processedData = $this->processAnuladoState($processedData);
                break;
                
            case 'PENDIENTE':
                $processedData = $this->processPendienteState($processedData);
                break;
        }

        return $processedData;
    }

    /**
     * Procesar estado ENTREGADO
     *
     * @param array $data
     * @return array
     */
    private function processEntregadoState(array $data): array
    {
        $data['fecha_entrega'] = $data['fecha_entrega'] ?? now()->format('Y-m-d');
        $data['cant_pndt'] = max(0, ($data['cantord'] ?? 0) - ($data['cantdpx'] ?? 0));
        
        // Validar cantidades
        if (($data['cantord'] ?? 0) <= 0) {
            throw new Exception('La cantidad ordenada debe ser mayor a cero');
        }
        
        if (($data['cantdpx'] ?? 0) <= 0) {
            throw new Exception('La cantidad entregada debe ser mayor a cero');
        }

        return $data;
    }

    /**
     * Procesar estado DESABASTECIDO
     *
     * @param array $data
     * @return array
     */
    private function processDesabastecidoState(array $data): array
    {
        $data['fecha_impresion'] = $data['fecha_impresion'] ?? now()->format('Y-m-d');
        return $data;
    }

    /**
     * Procesar estado ANULADO
     *
     * @param array $data
     * @return array
     */
    private function processAnuladoState(array $data): array
    {
        $data['fecha_anulado'] = $data['fecha_anulado'] ?? now()->format('Y-m-d');
        return $data;
    }

    /**
     * Procesar estado PENDIENTE
     *
     * @param array $data
     * @return array
     */
    private function processPendienteState(array $data): array
    {
        $data['fecha'] = now()->format('Y-m-d');
        return $data;
    }

    /**
     * Crear observación
     *
     * @param PendienteApiMedcol6 $pendiente
     * @param string $observacion
     * @return ObservacionesApiMedcol6
     */
    private function createObservacion(PendienteApiMedcol6 $pendiente, string $observacion): ObservacionesApiMedcol6
    {
        return ObservacionesApiMedcol6::create([
            'pendiente_id' => $pendiente->id,
            'factura' => $pendiente->factura,
            'documento' => $pendiente->documento,
            'observacion' => $observacion,
            'usuario' => Auth::user()->name,
            'fecha_observacion' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Crear registro de entregado
     *
     * @param PendienteApiMedcol6 $pendiente
     * @param array $data
     * @return EntregadosApiMedcol6
     */
    private function createEntregado(PendienteApiMedcol6 $pendiente, array $data): EntregadosApiMedcol6
    {
        return EntregadosApiMedcol6::create([
            // Campos requeridos de la tabla
            'Tipodocum' => $pendiente->Tipodocum ?? 'CC',
            'cantdpx' => $data['cantdpx'],
            'cantord' => $data['cantord'],
            'fecha_factura' => $pendiente->fecha_factura,
            'fecha' => $pendiente->fecha ?? now(), // Campo faltante que causaba el error
            'historia' => $pendiente->historia ?? '',
            'apellido1' => $pendiente->apellido1 ?? '',
            'apellido2' => $pendiente->apellido2 ?? '',
            'nombre1' => $pendiente->nombre1 ?? '',
            'nombre2' => $pendiente->nombre2 ?? '',
            'cantedad' => $pendiente->cantedad ?? '0',
            'direcres' => $pendiente->direcres,
            'telefres' => $pendiente->telefres,
            'documento' => $pendiente->documento,
            'factura' => $pendiente->factura,
            'orden_externa' => $pendiente->orden_externa ?? null,
            'codigo' => $pendiente->codigo,
            'nombre' => $pendiente->nombre,
            'cums' => $pendiente->cums ?? '',
            'cantidad' => $data['cantdpx'], // Cantidad entregada
            'cajero' => $pendiente->cajero ?? '',
            'usuario' => Auth::user()->name,
            'estado' => 'ENTREGADO',
            'fecha_entrega' => $data['fecha_entrega'],
            'doc_entrega' => $data['doc_entrega'],
            'factura_entrega' => $data['factura_entrega'],
            'centroproduccion' => $pendiente->centroproduccion ?? null,
            'observaciones' => $data['observacion'] ?? null,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Obtener pendientes con filtros
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getPendientesQuery(array $filters = [])
    {
        $query = PendienteApiMedcol6::query();

        // Filtro por estado
        if (isset($filters['estado'])) {
            if (is_array($filters['estado'])) {
                $query->whereIn('estado', $filters['estado']);
            } else {
                $query->where('estado', $filters['estado']);
            }
        } else {
            // Por defecto, solo pendientes
            $query->where(function ($q) {
                $q->where('estado', 'PENDIENTE')
                  ->orWhereNull('estado');
            });
        }

        // Filtro por droguería según usuario
        $drogueria = $this->getUserDrogueria();
        if ($drogueria) {
            $query->where('centroproduccion', $drogueria);
        }

        // Filtro por fechas
        if (isset($filters['fecha_desde'])) {
            $query->whereDate('fecha_factura', '>=', $filters['fecha_desde']);
        }

        if (isset($filters['fecha_hasta'])) {
            $query->whereDate('fecha_factura', '<=', $filters['fecha_hasta']);
        }

        // Filtro por documento
        if (isset($filters['documento'])) {
            $query->where('documento', 'like', '%' . $filters['documento'] . '%');
        }

        return $query;
    }

    /**
     * Obtener droguería del usuario autenticado
     *
     * @return string|null
     */
    private function getUserDrogueria(): ?string
    {
        $drogueriaMap = [
            '1' => null,     // Todos
            '2' => 'SM01',
            '3' => 'DLR1',
            '4' => 'PAC',
            '5' => 'EHU1',
            '6' => 'BIO1',
            '8' => 'EM01',
            '9' => 'BPDT',
            '10' => 'DPA1',
            '11' => 'EVSM',
            '12' => 'EVEN',
            '13' => 'FRJA',
        ];

        $userDrogueria = Auth::user()->drogueria ?? '1';
        return $drogueriaMap[$userDrogueria] ?? null;
    }

    /**
     * Validar datos de entrada
     *
     * @param array $data
     * @param string $estado
     * @return array
     */
    public function validateData(array $data, string $estado): array
    {
        $rules = [
            'estado' => 'required|in:PENDIENTE,ENTREGADO,DESABASTECIDO,ANULADO',
            'observacion' => 'required|string|min:3|max:500'
        ];

        $messages = [
            'estado.required' => 'El estado es requerido',
            'estado.in' => 'El estado seleccionado no es válido',
            'observacion.required' => 'Las observaciones son requeridas',
            'observacion.min' => 'Las observaciones deben tener al menos 3 caracteres',
            'observacion.max' => 'Las observaciones no pueden exceder 500 caracteres'
        ];

        // Reglas específicas por estado
        switch ($estado) {
            case 'ENTREGADO':
                $rules = array_merge($rules, [
                    'cantord' => 'required|numeric|min:1',
                    'cantdpx' => 'required|numeric|min:1',
                    'fecha_entrega' => 'required|date',
                    'factura_entrega' => 'required|string',
                    'doc_entrega' => 'required|string'
                ]);
                
                $messages = array_merge($messages, [
                    'cantord.required' => 'La cantidad ordenada es requerida',
                    'cantord.min' => 'La cantidad ordenada debe ser mayor a cero',
                    'cantdpx.required' => 'La cantidad entregada es requerida',
                    'cantdpx.min' => 'La cantidad entregada debe ser mayor a cero',
                    'fecha_entrega.required' => 'La fecha de entrega es requerida',
                    'factura_entrega.required' => 'La factura de entrega es requerida',
                    'doc_entrega.required' => 'El documento de entrega es requerido'
                ]);
                break;

            case 'DESABASTECIDO':
                $rules['fecha_impresion'] = 'required|date';
                $messages['fecha_impresion.required'] = 'La fecha de tramitado es requerida';
                break;

            case 'ANULADO':
                $rules['fecha_anulado'] = 'required|date';
                $messages['fecha_anulado.required'] = 'La fecha de anulación es requerida';
                break;
        }

        return ['rules' => $rules, 'messages' => $messages];
    }
}