<?php

namespace App\Models\compras;

use Illuminate\Database\Eloquent\Model;

class Molecula extends Model
{
    protected $table = 'medicamentos_api_medcol3'; // << usa la tabla existente
    protected $primaryKey = 'id';
    public $timestamps = true;

    // Campos reales de la tabla medicamentos_api_medcol3
    protected $fillable = [
        'tipo_MI',
        'codigo',         // << corresponde a tu antiguo "codigo_rfast"
        'nombre',         // << corresponde a tu antiguo "descripcion"
        'marca',
        'atc',
        'forma',          // << corresponde a tu antiguo "presentacion"
        'concentracion',
        'cums',
        'estado',         // puedes usarlo como "activo" si te sirve
    ];

    /**********************
     * Relaciones
     **********************/
    public function codigosProveedor()
    {
        // La FK sigue llamándose molecule_id en la tabla de relación
        return $this->hasMany(MoleculaProveedorCodigo::class, 'molecule_id');
    }

    /**********************
     * Scopes equivalentes
     **********************/
    // Activas vs estado (ajústalo a tu regla: por ejemplo estado = 'ACTIVO')
    public function scopeActivas($q)
    {
        return $q->where('estado', 'ACTIVO'); // o 1, o 'HABILITADO' según tu data
    }

    // Antes: scopePorCodigo($codigo_rfast) -> ahora mapea a 'codigo'
    public function scopePorCodigo($q, $codigoRfast)
    {
        return $q->where('codigo', $codigoRfast);
    }

    /**********************
     * Accessors/Mutators para no romper tus blades/ctrls
     * Exponen las "propiedades viejas" con los nombres que ya usas
     **********************/

    // codigo_rfast <-> codigo
    public function getCodigoRfastAttribute()
    {
        return $this->codigo;
    }
    public function setCodigoRfastAttribute($value)
    {
        $this->codigo = $value;
    }

    // descripcion <-> nombre
    public function getDescripcionAttribute()
    {
        return $this->nombre;
    }
    public function setDescripcionAttribute($value)
    {
        $this->nombre = $value;
    }

    // presentacion <-> forma
    public function getPresentacionAttribute()
    {
        return $this->forma;
    }
    public function setPresentacionAttribute($value)
    {
        $this->forma = $value;
    }

    // activo (boolean) virtual basado en 'estado' (ajústalo a tu lógica real)
    public function getActivoAttribute()
    {
        return in_array(strtoupper((string)$this->estado), ['ACTIVO','HABILITADO','1'], true);
    }
    public function setActivoAttribute($value)
    {
        // si alguien setea 'activo', lo traducimos a 'estado'
        $this->estado = $value ? 'ACTIVO' : 'INACTIVO';
    }

    // etiqueta que usabas en selects
    public function getEtiquetaAttribute()
    {
        return "{$this->codigo} — {$this->nombre}";
    }

    /**
     * Resolver el “código entrada” como antes (tu RFAST o código de proveedor)
     */
    public static function resolverPorCodigoEntrada(string $codigoEntrada, ?int $proveedorId = null): ?self
    {
        $codigoEntrada = trim($codigoEntrada);

        // 1) ¿Coincide con nuestro código (antes codigo_rfast)?
        $mol = static::where('codigo', $codigoEntrada)->first();
        if ($mol) return $mol;

        // 2) ¿Es un código de proveedor?
        $q = MoleculaProveedorCodigo::query()
              ->where('codigo_proveedor', $codigoEntrada)
              ->where('activo', true);

        if ($proveedorId) {
            $q->where('proveedor_id', $proveedorId);
        }

        $mpc = $q->first();
        return $mpc->molecula;
    }
}
