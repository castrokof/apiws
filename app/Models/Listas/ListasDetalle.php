<?php

namespace App\Models\Listas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListasDetalle extends Model
{
    protected $table = 'listasdetalle';

    protected $fillable = [
        'slug',
        'nombre',
        'descripcion',
        'activo',
        'listas_id',
        'user_id',
        ];


    public function userid()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function ListasId()
    {
                return $this->belongsTo(Listas::class, 'listas_id');
    }
}
