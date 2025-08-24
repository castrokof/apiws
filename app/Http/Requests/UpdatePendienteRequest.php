<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Request para validar la actualización de pendientes
 * 
 * Esta clase encapsula toda la lógica de validación para las actualizaciones
 * de pendientes, siguiendo el principio de responsabilidad única.
 */
class UpdatePendienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // La autorización se maneja en el middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $estado = $this->input('estado');
        
        // Reglas base que siempre aplican
        $rules = [
            'estado' => 'required|in:PENDIENTE,ENTREGADO,DESABASTECIDO,ANULADO,VENCIDO,SIN CONTACTO',
            'cantord' => 'required|numeric|min:1'
        ];

        // Reglas específicas por estado
        switch ($estado) {
            case 'ENTREGADO':
                $rules = array_merge($rules, [
                    'cantdpx' => 'required|numeric|min:1|lte:cantord',
                    'fecha_entrega' => 'required|date|after_or_equal:fecha_factura',
                    'factura_entrega' => 'required|string|max:50',
                    'doc_entrega' => 'required|string|max:50',
                    'observacion' => 'required|string|min:3|max:500'
                ]);
                break;

            case 'DESABASTECIDO':
                $rules = array_merge($rules, [
                    'cantdpx' => 'nullable|numeric|min:0|lte:cantord',
                    'fecha_impresion' => 'required|date|after_or_equal:fecha_factura',
                    'observacion' => 'nullable|string|max:500'
                ]);
                break;

            case 'ANULADO':
                $rules = array_merge($rules, [
                    'cantdpx' => 'nullable|numeric|min:0|lte:cantord',
                    'fecha_anulado' => 'required|date|after_or_equal:fecha_factura',
                    'observacion' => 'nullable|string|max:500'
                ]);
                break;

            case 'VENCIDO':
                $rules = array_merge($rules, [
                    'cantdpx' => 'nullable|numeric|min:0|lte:cantord',
                    'observacion' => 'nullable|string|max:500'
                ]);
                break;

            case 'SIN CONTACTO':
                $rules = array_merge($rules, [
                    'cantdpx' => 'nullable|numeric|min:0|lte:cantord',
                    'fecha_sincontacto' => 'required|date|after_or_equal:fecha_factura',
                    'observacion' => 'nullable|string|max:500'
                ]);
                break;

            case 'PENDIENTE':
            default:
                $rules = array_merge($rules, [
                    'cantdpx' => 'nullable|numeric|min:0|lte:cantord',
                    'observacion' => 'nullable|string|max:500'
                ]);
                break;
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // Mensajes generales
            'estado.required' => 'El estado es requerido',
            'estado.in' => 'El estado seleccionado no es válido',
            'observacion.required' => 'Las observaciones son requeridas',
            'observacion.min' => 'Las observaciones deben tener al menos 3 caracteres',
            'observacion.max' => 'Las observaciones no pueden exceder 500 caracteres',

            // Mensajes para estado ENTREGADO
            'cantord.required' => 'La cantidad ordenada es requerida',
            'cantord.min' => 'La cantidad ordenada debe ser mayor a cero',
            'cantord.numeric' => 'La cantidad ordenada debe ser un número',
            
            'cantdpx.required' => 'La cantidad entregada es requerida',
            'cantdpx.min' => 'La cantidad entregada debe ser mayor a cero',
            'cantdpx.numeric' => 'La cantidad entregada debe ser un número',
            'cantdpx.lte' => 'La cantidad entregada no puede ser mayor a la cantidad ordenada',
            
            'fecha_entrega.required' => 'La fecha de entrega es requerida',
            'fecha_entrega.date' => 'La fecha de entrega debe ser una fecha válida',
            'fecha_entrega.after_or_equal:fecha_factura' => 'La fecha de entrega debe ser posterior o igual a la fecha de la factura',

            'factura_entrega.required' => 'La factura de entrega es requerida',
            'factura_entrega.max' => 'La factura de entrega no puede exceder 50 caracteres',
            
            'doc_entrega.required' => 'El documento de entrega es requerido',
            'doc_entrega.max' => 'El documento de entrega no puede exceder 50 caracteres',

            // Mensajes para estado DESABASTECIDO
            'fecha_impresion.required' => 'La fecha de tramitado es requerida',
            'fecha_impresion.date' => 'La fecha de tramitado debe ser una fecha válida',
            'fecha_impresion.after_or_equal' => 'La fecha de tramitado debe ser posterior o igual a la fecha de la factura',
            'fecha_impresion.before_or_equal' => 'La fecha de tramitado no puede ser futura',

            // Mensajes para estado ANULADO
            'fecha_anulado.required' => 'La fecha de anulación es requerida',
            'fecha_anulado.date' => 'La fecha de anulación debe ser una fecha válida',
            'fecha_anulado.after_or_equal' => 'La fecha de anulación debe ser posterior o igual a la fecha de la factura',
            'fecha_anulado.before_or_equal' => 'La fecha de anulación no puede ser futura',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'cantord' => 'cantidad ordenada',
            'cantdpx' => 'cantidad entregada',
            'fecha_entrega' => 'fecha de entrega',
            'fecha_impresion' => 'fecha de tramitado',
            'fecha_anulado' => 'fecha de anulación',
            'factura_entrega' => 'factura de entrega',
            'doc_entrega' => 'documento de entrega',
            'observacion' => 'observaciones'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            // Validación personalizada: cantidad pendiente no puede ser negativa
            if ($this->input('estado') === 'ENTREGADO') {
                $cantord = (float) $this->input('cantord', 0);
                $cantdpx = (float) $this->input('cantdpx', 0);
                
                if ($cantdpx > $cantord) {
                    $validator->errors()->add('cantdpx', 'La cantidad entregada no puede ser mayor a la cantidad ordenada');
                }
            }
        });
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $errorMessages = $errors->all();
        
        // Crear mensaje estructurado para mejor visualización
        $formattedMessage = "❌ Errores de validación:\n\n";
        foreach ($errorMessages as $index => $error) {
            $formattedMessage .= "• " . $error . "\n";
        }
        
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'formatted_message' => $formattedMessage,
                'errors' => $errorMessages,
                'errors_by_field' => $errors->toArray(),
                'alert_type' => 'error',
                'show_alert' => true
            ], 422)
        );
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Limpiar y preparar datos antes de la validación
        $data = [];

        // Calcular cantidad pendiente automáticamente
        if ($this->has('cantord') && $this->has('cantdpx')) {
            $data['cant_pndt'] = max(0, (float) $this->input('cantord') - (float) $this->input('cantdpx'));
        }

        // Establecer fechas por defecto si no se proporcionan
        switch ($this->input('estado')) {
            case 'ENTREGADO':
                if (!$this->has('fecha_entrega') || empty($this->input('fecha_entrega'))) {
                    $data['fecha_entrega'] = now()->format('Y-m-d');
                }
                break;
                
            case 'DESABASTECIDO':
                if (!$this->has('fecha_impresion') || empty($this->input('fecha_impresion'))) {
                    $data['fecha_impresion'] = now()->format('Y-m-d');
                }
                break;
                
            case 'ANULADO':
                if (!$this->has('fecha_anulado') || empty($this->input('fecha_anulado'))) {
                    $data['fecha_anulado'] = now()->format('Y-m-d');
                }
                break;
        }

        // Limpiar texto de observaciones
        if ($this->has('observacion')) {
            $data['observacion'] = trim(strip_tags($this->input('observacion')));
        }

        $this->merge($data);
    }
}