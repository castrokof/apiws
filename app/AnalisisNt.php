<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalisisNt extends Model
{
    protected $table = 'analisis_nt';

    protected $fillable = [
        'codigo_cliente',
        'codigo_medcol',
        'agrupador',
        'nombre',
        'cums',
        'expediente',
        'valor_unitario',
        'frecuencia_uso',
        'contrato'
    ];

    protected $casts = [
        'valor_unitario' => 'decimal:2',
    ];

    public static function validationRules()
    {
        return [
            'codigo_cliente' => 'nullable|string|max:255',
            'codigo_medcol' => 'required|string|max:255',
            'agrupador' => 'nullable|string|max:255',
            'nombre' => 'required|string|max:255',
            'cums' => 'required|string|max:255',
            'expediente' => 'nullable|string|max:255',
            'valor_unitario' => 'nullable|numeric|min:0',
            'frecuencia_uso' => 'nullable|string|max:255',
            'contrato' => 'required|string|max:255'
        ];
    }

    public static function validationMessages()
    {
        return [
            'codigo_medcol.required' => 'El código medcol es requerido',
            'nombre.required' => 'El nombre es requerido',
            'cums.required' => 'El CUMS es requerido',
            'contrato.required' => 'El contrato es requerido',
            'valor_unitario.numeric' => 'El valor unitario debe ser un número',
            'valor_unitario.min' => 'El valor unitario no puede ser negativo'
        ];
    }
}
